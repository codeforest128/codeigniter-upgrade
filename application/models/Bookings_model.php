<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Bookings_model
 */
class Bookings_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library('Poco_Factory', 'poco_factory');
    }

    public function add($data)
    {
        //set default message as error
        $message = array(
            'type' => 'error',
            'message' => lang('book_failure')
        );

        //start a new SQL transaction
        $this->db->trans_begin();
        //insert the booking details
        $this->db->insert('bookings', $data);

        //if queries are not succesful otherwise update message to success and commit transaction
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();

            $message = array(
                'type' => 'message',
                'message' => lang('book_success')
            );
        }

        return $message;
    }

    public function approve($booking_id)
    {
        $message = array(
            'type' => 'error',
            'message' => lang('booking_approval_failure')
        );

        if(is_null($booking_id) || $booking_id == '') {
            return $message;
        }

        $this->db->update('bookings', array('provisional' => 0), array('booking_id' => $booking_id));

        if($this->db->affected_rows() > 0) {
            $message = array(
                'type' => 'message',
                'message' => lang('booking_approval_success')
            );
        }

        return $message;
    }

    public function check_for_conflicts($data, $booking_id = NULL)
    {
        //remove last '|'
        $session_ids = substr($data['id'], 0, -1);
        //make an array of session ids
        $session_ids = explode('|', $session_ids);

        $query = $this->get_query_for_booking_search($data['start_date']);

        $query .= " AND (";
        foreach($session_ids as $session_id) {
            $query .= "B.session_id LIKE '%" . $session_id . "|%' OR ";
        }
        //remove the last ' OR '
        $query = substr($query, 0, -4);
        $query .= ')';

        $query .= " AND B.sheet = " . $this->db->escape($data['sheet']);

        if(!is_null($booking_id)) {
            $query .= "AND B.booking_id != " . $booking_id;
        }

        $query = $this->db->query($query);

        return $query->num_rows();
    }

    public function decline($booking_id)
    {
        $message = $this->delete($booking_id);

        if($message['type'] != 'error') {
            $message['message'] = lang('booking_decline_success');
        }
        else {
            $message['message'] = lang('booking_decline_failure');
        }

        return $message;
    }

    public function delete($booking_id)
    {
        //set default message as error
        $message = array(
            'type' => 'error',
            'message' => lang('delete_booking_failure')
        );


        if(!isset($booking_id) || $booking_id == '') {
            return $message;
        }

        //start a new SQL transaction
        $this->db->trans_begin();

        $this->db->delete('id_bookings', array('booking_id' => $booking_id));

        //if queries are not succesful otherwise update message to success and commit transaction
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();

            $message = array(
                'type' => 'message',
                'message' => lang('delete_booking_success')
            );
        }

        return $message;
    }

    public function edit($data, $booking_id)
    {
        //set default message as error
        $message = array(
            'type' => 'error',
            'message' => lang('book_failure')
        );

        //start a new SQL transaction
        $this->db->trans_begin();

        $this->db->where('booking_id', $booking_id);
        $this->db->update('bookings', $data);

        //if queries are not successful rollback, otherwise update message to success and commit transaction
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();

            $message = array(
                'type' => 'message',
                'message' => lang('book_success')
            );
        }

        return $message;
    }

    public function get_auto_complete($field, $query, $double_field = FALSE)
    {
        $results = array();

        //if we have more than one field, use a seperate query
        if(!$double_field) {
            $this->db->distinct()->select($field)->like($field, $query)->from('bookings');
            $query = $this->db->get();
        }
        else {
            $query = $this->db->query('
                SELECT ' . $field . '_1 as team_name FROM id_bookings WHERE ' . $field . '_1 LIKE \'%' . $query . '%\'
                UNION
                SELECT ' . $field . '_2 as team_name FROM id_bookings WHERE ' . $field . '_2 LIKE \'%' . $query . '%\'
            ');
        }


        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row)
            {
                $results[] = $row[$field];
            }
        }

        return $results;
    }

    public function get_booking($booking_id)
    {
        if(!$booking_id || $booking_id == '' || $booking_id == NULL)
        {
            return FALSE;
        }

        $this->db->select('B.*, U.*, B.booking_date as start_date');
        $this->db->from('bookings as B');
        $this->db->join('users as U', 'U.id = B.user_id', 'left');
        $this->db->where('B.booking_id', $booking_id);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
        {
            return array($this->poco_factory->create_booking(null));
        }

        $booking = $this->poco_factory->create_booking($query->row());

        return $booking;
    }

    /**
     * @param $wc
     * @return array
     */
    public function get_bookings($wc)
    {
        $bookings = array();

        for($i = 0; $i < 7; $i++) {
            $strdate = strtotime('+' . $i . ' days', strtotime($wc));
            $date = date('Y-m-d', $strdate);

            // create empty array for each session
            // each session will have 5 elements inside for each sheet
            $bookings[$date] = array(
                1 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
                2 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
                3 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
                4 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
                5 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
                6 => array('A' => NULL, 'B' => NULL, 'C' => NULL, 'D' => NULL, 'E' => NULL),
            );

            $query = $this->db->query($this->get_query_for_booking_search($strdate));

            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $sessions = explode('|', $row->session_id);
                    if(end($sessions) == '') {
                        array_pop($sessions);
                    }

                    foreach($sessions as $session) {
                        $row->session = $session;
                        $booking = $this->poco_factory->create_booking($row);

                        $bookings[$date][$session][$row->sheet] = $booking;
                    }
                }
            }
        }

        return $bookings;
    }

    public function get_bookings_for_approval()
    {
        $bookings = array();

        $this->db->select('B.booking_id, B.title, B.sheet, B.session_id, B.booking_date as start_date, B.user_id, U.first_name, U.last_name');
        $this->db->from('bookings as B');
        $this->db->join('users as U', 'U.id = B.user_id', 'left');
        $this->db->where('B.provisional', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $booking = $this->poco_factory->create_booking($row);
                $sessions = explode('|', $booking->session);

                //remove last empty element from array
                if(end($sessions) == '') {
                    unset($sessions[count($sessions) -1]);
                }

                $booking->session = implode(',', $sessions);

                $bookings[] = $booking;
            }
        }

        return $bookings;
    }

    public function get_count_bookings_for_approval()
    {
        $this->db->where('provisional', 1);
        $this->db->from('bookings');

        return $this->db->count_all_results();
    }

    private function get_query_for_booking_search($strdate)
    {
        return "SELECT *
                FROM `id_bookings` as B
                    RIGHT JOIN (
                        SELECT 12 * ( YEAR(FROM_UNIXTIME(" . $strdate . ")) - YEAR(FROM_UNIXTIME(booking_date)) )
                                    + (MONTH(FROM_UNIXTIME(" . $strdate . "))
                                    - MONTH(FROM_UNIXTIME(booking_date))) AS months,
                                    DAYOFMONTH(FROM_UNIXTIME(" . $strdate . ")) as current_month_date,
                                    DAYOFMONTH(FROM_UNIXTIME(booking_date)) as booking_month_date,
                                    repeat_month_interval as month_interval,
		                            booking_id
                                FROM id_bookings
                    )  as months ON months.booking_id = B.booking_id
                WHERE (((
                        (
                            (
                              repeat_year LIKE '%" . date('Y', $strdate) . "%'
                              OR repeat_year = '*'
                            )
                            AND (
                              repeat_month LIKE '%" . date('n', $strdate) . "%'
                              OR repeat_month = '*'
                            )
                            AND (
                              repeat_week_im LIKE '%" . week_of_month($strdate) . "%'
                              OR repeat_week_im = '*'
                            )
                            AND (
                                repeat_day_im = '" . date('j', $strdate) . "'
                            )
                        AND booking_date < " . $strdate . "
                    )
                    AND (
                        " . $strdate . " <= repeat_ends_time
                        OR repeat_ends_time IS NULL
                        OR repeat_ends_time = ''
                    )
                    AND (repeat_interval IS NULL OR repeat_interval ='')
                    )
                )
                OR (
                    repeat_interval IS NOT NULL
                    AND (
                        " . $strdate . " > booking_date
                        AND ((" . $strdate . " - booking_date) % repeat_interval) = 0
                        AND (
                            " . $strdate . " <= repeat_ends_time
                            OR repeat_ends_time IS NULL
                            OR repeat_ends_time = ''
                        )
                    )
                )
                OR (
                    repeat_month_interval IS NOT NULL
                    AND (
                        " . $strdate . " <= repeat_ends_time
                        OR repeat_ends_time IS NULL
                        OR repeat_ends_time = ''
                    )
                    AND (
                            (
                                months.current_month_date = months.booking_month_date
                                    AND months.months % repeat_month_interval = 0
                                    AND repeat_week_im IS NULL
                                    AND repeat_weekday IS NULL
                    		)
                    		OR (
                    			months.months % repeat_month_interval = 0
                                    AND (
                                        repeat_week_im LIKE '%" . week_of_month($strdate) . "%'
                                        OR repeat_week_im = '*'
                                    )
                                    AND (
                                        repeat_weekday LIKE '%" . date('N', $strdate) . "%'
                                        OR repeat_weekday = '*'
                                    )
                    		)
                    )
                )
                OR booking_date = " . $strdate . ")
            ";
    }

    public function search_for_bookings($terms)
    {
        $bookings = array();

        if(!isset($terms['start_date'])) {
            $this->db->select('B.*, B.booking_date as start_date');
            $this->db->from('bookings as B');

            if(isset($terms['title'])) {
                $this->db->like('title', $terms['title']);
                unset($terms['title']);
            }

            if(isset($terms['session_id'])) {
                $this->db->like('session_id', $terms['session_id']);
                unset($terms['session_id']);
            }

            if(isset($terms['team'])) {
                $this->db->like('team_name_1', $terms['team']);
                $this->db->or_like('team_name_2', $terms['team']);
                unset($terms['team']);
            }

            if(isset($terms['start_time']) && isset($terms['end_time'])) {
                $terms['REPLACE(start_time, \':\', \'\') <='] = str_replace(':', '', $terms['start_time']);
                unset($terms['start_time']);
                $terms['REPLACE(end_time, \':\', \'\') >='] = str_replace(':', '', $terms['end_time']);
                unset($terms['end_time']);
            }

            if(!empty($terms)) {
                $this->db->where($terms);
            }

            $query = $this->db->get();

            if($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    $bookings[] = $this->poco_factory->create_booking($row);
                }
            }
        }
        else {
            //we cannot search without an end date otherwise we will fall into infinate loop
            if(!isset($terms['end_date'])) {
                return FALSE;
            }

            //determine the difference between the dates
            $start_date = date_to_unix($terms['start_date']);
            $end_date = date_to_unix($terms['end_date']);
            $datediff = $end_date - $start_date;
            $days = floor($datediff/86400);

            $strdate = $start_date;

            $found_ids = array();

            // remove our start and end dates so they dont get added to the where clause
            unset($terms['start_date']);
            unset($terms['end_date']);

            // start looping through dates
            for($i = 0; $i < $days; $i++) {
                $strdate = strtotime('+ 1 days', $strdate);

                $query = $this->get_query_for_booking_search($strdate);

                foreach($terms as $column => $value) {
                    if($column == 'title' || $column == 'session_id') {
                        $query .= " AND " . $column . " LIKE '%" . $value . "%'";
                    }
                    elseif($column == 'team') {
                        $query .= " AND team_name_1 LIKE '%" . $value . "%'";
                        $query .= " AND team_name_2 LIKE '%" . $value . "%'";
                    }
                    elseif($column == 'start_time') {
                        $query .= " AND REPLACE(" . $column . ", ':', '') <= " . str_replace(':', '', $terms['start_time']);
                    }
                    elseif($column == 'end_time') {
                        $query .= " AND REPLACE(" . $column . ", ':', '') <= " . str_replace(':', '', $terms['end_time']);
                    }
                    else {
                        $query .= " AND " . $column . " = " . $value;
                    }
                }

                if(count($found_ids) > 0) {
                    $query .= " AND B.booking_id NOT IN (" . implode(',', $found_ids) . ")";
                }

                $query = $this->db->query($query);

                if($query->num_rows() > 0) {
                    foreach($query->result() as $row) {
                        $found_ids[] = $row->booking_id;
                        $bookings[] = $this->poco_factory->create_booking($row);
                    }
                }
            }
            // end looping through dates
        }

        return $bookings;
    }
}