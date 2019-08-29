<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poco_factory
{
    public function create_booking($data = NULL)
    {
        require_once(__DIR__ . '/POCOs/Booking.php');
        $booking = new Booking();

        $booking->id = isset($data->booking_id) ? $data->booking_id : '';
        $booking->title = isset($data->title) ? $data->title : '';
        $booking->session = isset($data->id) ? $data->id : '';
        if(isset($data->id)) {
            $booking->session = $data->id;
            $sessions = explode('|', $booking->session);
            if(end($sessions) == '') {
                array_pop($sessions);
            }
            $booking->session_str = implode(',', $sessions);
        }
        else {
            $booking->session = '';
        }
        $booking->sheet = isset($data->sheet) ? $data->sheet : '';
        $booking->start_time = isset($data->start_time) ? $data->start_time : '00:00';
        $booking->end_time = isset($data->end_time) ? $data->end_time : '00:00';
        $booking->team_name_1 = isset($data->team_name_1) ? $data->team_name_1 : '';
        $booking->team_name_2 = isset($data->team_name_2) ? $data->team_name_2 : '';
        $booking->score_1 = isset($data->score_1) ? $data->score_1 : '';
        $booking->score_2 = isset($data->score_2) ? $data->score_2 : '';
        $booking->provisional = isset($data->provisional) ? $data->provisional : '';
        $booking->invoiced = isset($data->invoiced) ? $data->invoiced : '';
        $booking->paid = isset($data->paid) ? $data->paid : '';

        $booking->user = isset($data->user_id) ? $this->create_user($data) : NULL;

        $booking->start_date = isset($data->start_date) ? $data->start_date : NULL;
        $booking->repeat_interval = isset($data->repeat_interval) ? $data->repeat_interval : NULL;
        $booking->repeat_month_interval = isset($data->repeat_month_interval) ? $data->repeat_month_interval : NULL;
        $booking->repeat_year = isset($data->repeat_year) ? $data->repeat_year : NULL;
        $booking->repeat_month = isset($data->repeat_month) ? $data->repeat_month : NULL;
        $booking->repeat_week_im = isset($data->repeat_week_im) ? $data->repeat_week_im : NULL;
        $booking->repeat_weekday = isset($data->repeat_weekday) ? $data->repeat_weekday : NULL;
        $booking->repeat_end = isset($data->repeat_end) ? $data->repeat_end : NULL;
        $booking->repeat_meta_id = isset($data->repeat_meta_id) ? $data->repeat_meta_id : NULL;

        $booking->repeat = isset($data->repeat) ? $data->repeat : NULL;
        $booking->repeat_every = isset($data->repeat_every) ? $data->repeat_every : NULL;
        $booking->repeat_by = isset($data->repeat_by) ? $data->repeat_by : NULL;
        $booking->repeat_on = isset($data->repeat_on) ? $data->repeat_on : NULL;
        $booking->repeat_ends = isset($data->repeat_ends) ? $data->repeat_ends : NULL;
        $booking->repeat_ends_after = isset($data->repeat_ends_after) ? $data->repeat_ends_after : NULL;
        $booking->repeat_ends_on = isset($data->repeat_ends_on) ? $data->repeat_ends_on : NULL;

        $booking->link = site_url('bookings/edit/' . $booking->id);
        $booking->link_class = $booking->provisional == 0 ? 'booked' : 'booked-provisional';

        $booking->link_text = '<span class="title">' . $booking->title . '<small>' . $booking->start_time . ' - ' . $booking->end_time . '</small></span>';
        $booking->link_text .= '<span class="content">';

            if($booking->team_name_1 != '' && $booking->team_name_2 != '') {
                $booking->link_text .= $booking->team_name_1;
                $booking->link_text .= $booking->score_1 != '' ? ' ' . $booking->score_1 : '';
                $booking->link_text .= ' v ';
                $booking->link_text .= $booking->team_name_2;
                $booking->link_text .= $booking->score_2 != '' ? ' ' . $booking->score_2 : '';
                $booking->link_text .= '<br />';
            }

        $booking->link_text .= '</span>';

        return $booking;
    }

    public function create_user($data)
    {
        require_once(__DIR__ . '/POCOs/User.php');
        $user = new User();

        $user->id = isset($data->user_id) ? $data->user_id : '';
        $user->username = isset($data->username) ? $data->username : '';
        $user->email = isset($data->email) ? $data->email : '';
        $user->first_name = isset($data->first_name) ? $data->first_name : '';
        $user->last_name = isset($data->last_name) ? $data->last_name : '';
        $user->phone = isset($data->phone) ? $data->phone : '';
        $user->address_1 = isset($data->address_1) ? $data->address_1 : '';
        $user->address_2 = isset($data->address_2) ? $data->address_2 : '';
        $user->address_3 = isset($data->address_3) ? $data->address_3 : '';
        $user->city = isset($data->city) ? $data->city : '';
        $user->postcode = isset($data->postcode) ? $data->postcode : '';
        $user->curling_club = isset($data->curling_club) ? $data->curling_club : '';
        $user->mailing_list = isset($data->mailing_list) ? $data->mailing_list : '';

        return $user;
    }

    public function create_session($data)
    {
        require_once(__DIR__ . '/POCOs/Session.php');
        $session = new Session();

        $session->id = isset($data->id) ? $data->id : '';
        $session->date = isset($data->session_start) ? date('d/m/Y', mysql_to_unix($data->session_start)) : '';
        $session->start_time = isset($data->session_start) ? date('H:i', mysql_to_unix($data->session_start)) : '';
        $session->end_time = isset($data->session_end) ? date('H:i', mysql_to_unix($data->session_end)) : '';
        $session->session_number = isset($data->session_number) ? $data->session_number : '';

        return $session;
    }
}