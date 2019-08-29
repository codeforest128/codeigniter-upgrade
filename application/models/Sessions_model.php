<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Sessions_model
 */
class Sessions_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library('Poco_Factory');
    }

    public function add($data)
    {
        $message = array(
            'type' => 'error',
            'message' => lang('add_session_failure')
        );

        if($data == NULL)
        {
            return $message;
        }

        $this->db->insert('ci_sessions', $data);

        if($this->db->affected_rows() == 0) {
            return $message;
        }

        $message = array(
            'type' => 'message',
            'message' => lang('add_session_success')
        );

        return $message;
    }

    public function check_for_conflicts($date, $session, $session_id = NULL)
    {
        $this->db->select('id');
        $this->db->like('session_start', $date);
        $this->db->where('session_number', $session);

        if(!is_null($session_id)) {
            $this->db->where('id !=', $session_id);
        }

        $query = $this->db->get('ci_sessions');

        return $query->num_rows();
    }

    public function delete($session_id)
    {
        //set default message as error
        $message = array(
            'type' => 'error',
            'message' => lang('delete_session_failure')
        );


        if(!isset($session_id) || $session_id == '') {
            return $message;
        }

        $this->db->delete('ci_sessions', array('id' => $session_id));

        $message = array(
            'type' => 'message',
            'message' => lang('delete_session_success')
        );

        return $message;
    }

    public function edit($data, $session_id)
    {
        $message = array(
            'type' => 'error',
            'message' => lang('edit_session_failure')
        );

        if($session_id == NULL || $data == NULL)
        {
            return $message;
        }

        $this->db->where('id', $session_id);
        $this->db->update('ci_sessions', $data);

        if($this->db->affected_rows() == 0) {
            return $message;
        }

        $message = array(
            'type' => 'message',
            'message' => lang('edit_session_success')
        );

        return $message;
    }

    public function get_custom_sessions()
    {
        $sessions = array();
        $date = date_to_mysql();
        $date = $this->db->escape($date);

        $this->db->select('*');
        $this->db->from('ci_sessions');
        $this->db->where('session_start > ' . $date);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $sessions = $query->result();
        }

        return $sessions;
    }

    public function get_session($session_id)
    {
        $session = FALSE;
        if(is_null($session_id) || $session_id == '') {
            show_404();
        }

        $query = $this->db->get_where('ci_sessions', array('id' => $session_id));
        if ($query->num_rows() > 0) {
            $session = $this->poco_factory->create_session($query->row());
        }

        return $session;
    }

    public function get_session_defaults_by_day($day)
    {
        $sessions = array();

        $query = $this->db->query('
                SELECT *
                    FROM id_sessions
                    WHERE session_start LIKE \'1970-01-0' . $day . '%\'
                    ORDER BY session_start
                ');

        if ($query->num_rows() > 0) {
            foreach($query->result() as $row) {
                $sessions[] = $this->poco_factory->create_session($row);
            }
        }

        return $sessions;
    }

    public function get_session_by_date($date, $session_number)
    {
        $session = array();

        if(is_null($date) || is_null($session_number)) {
            show_404();
        }

        //get the day of the week for the selected date so if we are using defaults we get the correct set (each day is different)
        $day_of_week = date('N', strtotime($date));

        //we just want to compare against the date not the date time so split and just grab the date
        $datetime = explode(' ', $date);
        $date = $datetime[0];
        $query = $this->db->query('
                SELECT s1.*
                    FROM id_sessions s1
                    WHERE (s1.session_start LIKE \'' . $date . '%\' OR s1.session_start LIKE \'1970-01-0' . $day_of_week . '%\')
                        AND session_number = ' . $session_number . '
                ');
        if ($query->num_rows() > 0) {
            $session = $query->row();

            // loop through and get the highest date result
            // if we have a default 1970 result, this will be replaced by the higher current date
            foreach($query->result() as $row) {
                if($row->session_start > $session->session_start) {
                    $session = $row;
                }
            }

            $session = $this->poco_factory->create_session($session);
        }
        return $session;
    }

    /**
     * @param $wc
     * @return array
     */
    public function get_sessions($wc)
    {
        $sessions = array();

        for($i = 0; $i < 7; $i++) {
            $unix_date = strtotime('+' . $i . ' days', strtotime($wc));
            $date = date('Y-m-d', $unix_date);
                    // print_r($date);

            //get the day of the week for the selected date so if we are using defaults we get the correct set (each day is different)
            $day_of_week = date('N', $unix_date);
            //         print_r($day_of_week); exit();

            $sessions[$date] = array();

            $query = $this->db->query('
                SELECT s1.*
                    FROM id_sessions s1
                    WHERE (s1.session_start LIKE \'' . $date . '%\' OR s1.session_start LIKE \'1970-01-0' . $day_of_week . '%\')
                    ORDER BY session_number
                ');


            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    // if there is no element with that session number add it,
                    // or if the new date is higher than the session_start in there, update with the new details
                    if(!array_key_exists($row->session_number, $sessions[$date]) || $row->session_start > $sessions[$date][$row->session_number]->session_start) {
                        $sessions[$date][$row->session_number] = $row;
                    }
                }
            }
        }

        return $sessions;
    }
}