<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Id_Base.php');

class Bookings extends Ice_diary_base
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Sessions_model', 'sessions_model');
        $this->load->model('Ion_Auth_Model', 'ion_auth_model');
        $this->load->helper('url');
        $this->load->helper('ice_diary');
  
    }

    public function add()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        //checking if were editing or creating
        //set up some default data
        if($this->uri->segment('2') == 'edit') {
            $booking_id = $this->uri->segment(3);

            if(!$booking_id) {
                show_404();
            }

            $booking = $this->bookings_model->get_booking($booking_id);
            print_r($booking);exit();

            $data['year'] = date('Y', $booking->start_date);
            $data['month'] = date('m', $booking->start_date);
            $data['day'] = date('d', $booking->start_date);
            $data['ci_session'] = explode('|', $booking->session);
            print_r($data['ci_session']);exit();
            $data['sheet'] = $booking->sheet;

            $start_time = explode(':', $booking->start_time);
            $data['start_time_hours'] = $start_time[0];
            $data['start_time_mins'] = $start_time[1];

            $end_time = explode(':', $booking->end_time);
            $data['end_time_hours'] = $end_time[0];
            $data['end_time_mins'] = $end_time[1];

            //url for cancel, tries to redirect back to where the user came from
            $cancel_uri = 'Diary/index/' . $data['year'] . '/' . $data['month'] . '/' . $data['day'];
        }
        else {
            require_once(__DIR__ . '/../libraries/POCOs/Booking.php');
            $booking = new Booking();

            $data['year'] = $this->uri->segment(3, TRUE);
            $data['month'] = $this->uri->segment(4, TRUE);
            $data['day'] = $this->uri->segment(5, TRUE);
            $data['ci_session'] = $this->uri->segment(6, TRUE);
            $data['sheet'] = $this->uri->segment(7, TRUE);

            //url for cancel, tries to redirect back to where the user came from
            $cancel_uri = 'diary/index/' . $data['year'] . '/' . $data['month'] . '/' . $data['day'];
        }

        $data['date'] = '';

        if($data['year'] && $data['month'] && $data['day']) {
            $data['date'] = date('d/m/Y', strtotime($data['year'] . '-' . $data['month'] . '-' . $data['day']));
        }

        $session_to_get = $data['ci_session'];
        if(is_array($session_to_get)) {
            $session_to_get = $session_to_get[0];
        }
        $session_data = $this->sessions_model->get_session_by_date(date_to_mysql($data['date']), $session_to_get);
        // if we dont have a start and end time, e.g. were not editing
        // load from the session data
        if(!isset($data['start_time_hours']) && !isset($data['start_time_mins'])) {
            $start_time = explode(':', $session_data->start_time);
            $data['start_time_hours'] = $start_time[0];
            $data['start_time_mins'] = $start_time[1];
        }

        if(!isset($data['end_time_hours']) && !isset($data['end_time_mins'])) {
            $end_time = explode(':', $session_data->end_time);
            $data['end_time_hours'] = $end_time[0];
            $data['end_time_mins'] = $end_time[1];
        }

        $data['is_admin'] = $this->is_admin;

        //if were posting back and cancel was clicked
        if(isset($_POST['cancel'])) {
            redirect($cancel_uri);
        }

        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('book_validation_title_label'), 'required');
        $this->form_validation->set_rules('start_date', $this->lang->line('book_validation_sdate_label'), 'required|callback_valid_date');
        $this->form_validation->set_rules('sheet', $this->lang->line('book_validation_sheet_label'), 'required');
        // $this->form_validation->set_rules('id', $this->lang->line('book_validation_session_label'), 'required');
        $this->form_validation->set_rules('team1', $this->lang->line('book_validation_team1_label'));
        $this->form_validation->set_rules('team2', $this->lang->line('book_validation_team2_label'));
        $this->form_validation->set_rules('score1', $this->lang->line('book_validation_score1_label'));
        $this->form_validation->set_rules('score2', $this->lang->line('book_validation_score2_label'));
        $this->form_validation->set_rules('provisional', $this->lang->line('book_validation_provisional_label'));
        $this->form_validation->set_rules('paid', $this->lang->line('book_validation_paid_label'));
        $this->form_validation->set_rules('invoiced', $this->lang->line('book_validation_invoiced_label'));
        $this->form_validation->set_rules('repeats', $this->lang->line('book_validation_repeats_label'));
        $this->form_validation->set_rules('repeats_every', $this->lang->line('book_validation_repeats_every_label'));
        $this->form_validation->set_rules('repeat_by', $this->lang->line('book_validation_repeat_by_label'));
        $this->form_validation->set_rules('repeats_on_mon', $this->lang->line('book_validation_repeats_on_mon_label'));
        $this->form_validation->set_rules('repeats_on_tue', $this->lang->line('book_validation_repeats_on_tue_label'));
        $this->form_validation->set_rules('repeats_on_wed', $this->lang->line('book_validation_repeats_on_wed_label'));
        $this->form_validation->set_rules('repeats_on_thu', $this->lang->line('book_validation_repeats_on_thu_label'));
        $this->form_validation->set_rules('repeats_on_fri', $this->lang->line('book_validation_repeats_on_fri_label'));
        $this->form_validation->set_rules('repeats_on_sat', $this->lang->line('book_validation_repeats_on_sat_label'));
        $this->form_validation->set_rules('repeats_on_sun', $this->lang->line('book_validation_repeats_on_sun_label'));
        $this->form_validation->set_rules('repeat_ends', $this->lang->line('book_validation_repeat_ends_label'));
        $this->form_validation->set_rules('repeat_ends_after_occurences', $this->lang->line('book_validation_repeats_on_occurences_label'), 'is_natural_no_zero|callback_repeat_ends_occurences[repeat_ends]');
        $this->form_validation->set_rules('repeat_ends_on_date', $this->lang->line('book_validation_repeats_end_after_date_label'), 'xcallback_repeat_ends_date[repeat_ends]');

        $data['error'] = '';
        $data['message'] = '';
        if ($this->form_validation->run() == true) {
            $insert['user_id'] = $this->current_user->id;
            $insert['title'] = $this->input->post('title', TRUE);
            $insert['session_id'] = is_array($this->input->post('id')) ? implode('|', $this->input->post('id', TRUE)) . '|' : $this->input->post('id', TRUE) . '|';
            $insert['sheet'] = $this->input->post('sheet', TRUE);
            $insert['start_time'] = $this->input->post('start_time_hours', TRUE) . ':' . $this->input->post('start_time_mins');
            $insert['end_time'] = $this->input->post('end_time_hours', TRUE) . ':' . $this->input->post('end_time_mins');
            $insert['team_name_1'] = $this->input->post('team1') != '' ? $this->input->post('team1', TRUE) : NULL;
            $insert['team_name_2'] = $this->input->post('team2') != '' ? $this->input->post('team2', TRUE) : NULL;
            $insert['score_1'] = $this->input->post('score1') != '' ? $this->input->post('score1', TRUE) : NULL;
            $insert['score_2'] = $this->input->post('score2') != '' ? $this->input->post('score2', TRUE) : NULL;
            $insert['provisional'] = $this->input->post('provisional') ? 1 : 0;
            $insert['paid'] = $this->input->post('paid') ? 1 : 0;
            $insert['invoiced'] = $this->input->post('invoiced') ? 1 : 0;
            $insert['repeat'] = $this->input->post('repeats') != '' ? $this->input->post('repeats', TRUE) : NULL;
            $insert['repeat_every'] = $this->input->post('repeats_every') != '' ? $this->input->post('repeats_every', TRUE) : NULL;
            $insert['repeat_by'] = $this->input->post('repeat_by') != '' ? $this->input->post('repeat_by', TRUE) : NULL;
            $insert['repeat_ends'] = $this->input->post('repeat_ends') != '' ? $this->input->post('repeat_ends', TRUE) : NULL;
            $insert['repeat_ends_after'] = $this->input->post('repeat_ends_after_occurences') != '' ? $this->input->post('repeat_ends_after_occurences', TRUE) : NULL;
            $insert['repeat_ends_on'] = $this->input->post('repeat_ends_on_date') != '' ? date_to_unix($this->input->post('repeat_ends_on_date', TRUE)) : NULL;
            $insert['booking_date'] = date_to_unix($this->input->post('start_date'), TRUE);

            // if the logged in user is not an admin, force provisional to true
            if(!$this->is_admin) {
                $insert['provisional'] = 1;
            }

            $unix_startdate = date_to_unix($this->input->post('start_date'), TRUE);

            if(!isset($booking_id)) {
                $booking_id = NULL;
            }

            $details = array(
                'start_date' => $unix_startdate,
                'id' => $insert['session_id'],
                'sheet' => $insert['sheet']
            );
            //check for conflicts(duplicates) and throw error if appropriate
            $conflicts = $this->bookings_model->check_for_conflicts($details, $booking_id);
            if($conflicts > 0) {
                $data['error'] = lang('book_duplicate_found');
            }
        }

        //if we have found no duplicates in the previous step
        if ($this->form_validation->run() == true && $data['error'] == '') {
            $repeat_ends = $this->input->post('repeat_ends');
            $occurences = $this->input->post('repeat_ends_after_occurences', TRUE);
            $repeats = $this->input->post('repeats', TRUE);
            $repeats_every = $this->input->post('repeats_every', TRUE);

            if($repeat_ends == 'after') {
                $repeat_end_date = calculate_booking_end_after($unix_startdate, $occurences, $repeats, $repeats_every);
                $insert['repeat_ends_time'] = $repeat_end_date;
            }
            elseif($repeat_ends == 'on') {
                $repeat_end_date = calculate_booking_end_on($this->input->post('repeat_ends_on_date'));
                $insert['repeat_ends_time'] = $repeat_end_date;
            }

            switch($this->input->post('repeats')) {
                default:
                case '':
                    break;
                case 'yearly':
                    $insert['repeat_year'] = $this->input->post('repeat_ends') == 'never' ? '*' : calculate_years_for_repeat(date('Y', $unix_startdate), $this->input->post('repeats_every'), date('Y', $repeat_end_date));
                    $insert['repeat_month'] = date('n', $unix_startdate);
                    $insert['repeat_week_im'] = week_of_month($insert['booking_date']);
                    $insert['repeat_day_im'] = date('j', $unix_startdate);
                    break;
                case 'monthly':
                    $insert['repeat_month_interval'] = $this->input->post('repeats_every', TRUE);

                    if($this->input->post('repeat_by') == 'week') {
                        $insert['repeat_week_im'] = week_of_month($insert['booking_date']);
                        $insert['repeat_weekday'] = date('N', $insert['booking_date']) . '|';
                    }
                    break;
                case 'weekly':
                    $insert['repeat_interval'] = 604800 * (int)$this->input->post('repeats_every', TRUE);
                    break;
                case 'daily':
                    $insert['repeat_interval'] = 86400 * (int)$this->input->post('repeats_every', TRUE);
                    break;
            }

            //if we are updating a pre-existing booking
            if($this->input->post('booking_id') != '') {
                $booking_id = $this->input->post('booking_id');
                $message = $this->bookings_model->edit($insert, $booking_id);
            }
            else {
                $message = $this->bookings_model->add($insert);

                if($message['type'] != 'error' && $insert['provisional'] == 1) {
                    $email_data = array(
                        'date' => $unix_startdate,
                        'start_time' => $insert['start_time'],
                        'end_time' => $insert['end_time'],
                        'user' => $this->ion_auth->user($insert['user_id'])->row()
                    );
                    $this->notify_admin($email_data);
                }
            }

            $uri = 'Diary/index/' . $data['year'] . '/' . $data['month'] . '/' . $data['day'];

            if($message['type'] == 'error') {
                $uri = $this->uri->uri_string();
            }

            $this->session->set_flashdata($message['type'], $message['message']);
            redirect($uri, 'refresh');
        }
        else
        {
            $start_date = $this->form_validation->set_value('start_date', $data['date']);
            $session = $this->form_validation->set_value('id', $data['ci_session']);
            $sheet = $this->form_validation->set_value('sheet', $data['sheet']);

            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $data['message'] .= $this->session->flashdata('message');
            $data['error'] .= validation_errors() . $this->session->flashdata('error');

            $data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'data-provide' => 'typeahead',
                'data-field' => 'title',
                'autocomplete' => 'off',
                'class' => 'typeahead',
                'value' => $this->form_validation->set_value('title', $booking->title)
            );
            $data['start_date'] = array(
                'name' => 'start_date',
                'id' => 'start_date',
                'type' => 'text',
                'value' => $start_date,
                'class' => 'datepicker'
            );
            $data['ci_session'] = array(
                'name' => 'id[]',
                'attr' => 'id="session" size="6"',
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6'
                ),
                'value' => $session
            );
            $data['sheet'] = array(
                'name' => 'sheet',
                'attr' => 'id="sheet"',
                'options' => array(
                    '' => 'Select a sheet',
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E'
                ),
                'value' => $sheet
            );

            $hours = create_hour_array();
            $minutes = create_minute_array();

            $data['start_time_hours'] = array(
                'name' => 'start_time_hours',
                'attr' => 'id="start_time_hours" class="span2"',
                'options' => $hours,
                'value' => $this->form_validation->set_value('start_time_hours', $data['start_time_hours'])
            );
            $data['start_time_mins'] = array(
                'name' => 'start_time_mins',
                'attr' => 'id="start_time_mins" class="span2"',
                'options' => $minutes,
                'value' => $this->form_validation->set_value('start_time_mins', $data['start_time_mins'])
            );

            $data['end_time_hours'] = array(
                'name' => 'end_time_hours',
                'attr' => 'id="end_time_hours" class="span2"',
                'options' => $hours,
                'value' => $this->form_validation->set_value('end_time_hours', $data['end_time_hours'])
            );
            $data['end_time_mins'] = array(
                'name' => 'end_time_mins',
                'attr' => 'id="end_time_mins" class="span2"',
                'options' => $minutes,
                'value' => $this->form_validation->set_value('end_time_mins', $data['end_time_mins'])
            );

            $data['team1'] = array(
                'name' => 'team1',
                'id' => 'team1',
                'type' => 'text',
                'data-provide' => 'typeahead',
                'data-field' => 'team_name',
                'autocomplete' => 'off',
                'class' => 'typeahead',
                'value' => $this->form_validation->set_value('team1', $booking->team_name_1)
            );
            $data['team2'] = array(
                'name' => 'team2',
                'id' => 'team2',
                'type' => 'text',
                'data-provide' => 'typeahead',
                'data-field' => 'team_name',
                'autocomplete' => 'off',
                'class' => 'typeahead',
                'value' => $this->form_validation->set_value('team2', $booking->team_name_2)
            );
            $data['score1'] = array(
                'name' => 'score1',
                'id' => 'score1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('score1', $booking->score_1)
            );
            $data['score2'] = array(
                'name' => 'score2',
                'id' => 'score2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('score2', $booking->score_2)
            );
            $provisional = $this->form_validation->set_value('provisional', $booking->provisional);
            $data['provisional'] = array(
                'name' => 'provisional',
                'id' => 'provisional',
                'value' => '1',
                'checked' => !is_null($provisional) && $provisional != '' && $provisional == '1'
            );
            $paid = $this->form_validation->set_value('paid', $booking->paid);
            $data['paid'] = array(
                'name' => 'paid',
                'id' => 'paid',
                'value' => '1',
                'checked' => !is_null($paid) && $paid != '' && $paid == '1'
            );
            $invoiced = $this->form_validation->set_value('invoiced', $booking->invoiced);
            $data['invoiced'] = array(
                'name' => 'invoiced',
                'id' => 'invoiced',
                'value' => '1',
                'checked' => !is_null($invoiced) && $invoiced != '' && $invoiced == '1'
            );
            $data['repeats'] = array(
                'name' => 'repeats',
                'attr' => 'id="repeats"',
                'options' => array(
                    '' => 'Does not repeat',
                    'daily' => 'Daily',
                    'weekly' => 'Weekly',
                    'monthly' => 'Monthly',
                    'yearly' => 'Yearly',
                ),
                'value' => $this->form_validation->set_value('repeats', $booking->repeat)
            );
            $data['repeats_every'] = array(
                'name' => 'repeats_every',
                'attr' => 'id="repeats_every"',
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12'),
                'value' => $this->form_validation->set_value('repeats_every', explode('|', $booking->repeat_every))
            );
            $data['repeat_by_month'] = array(
                'name' => 'repeat_by',
                'id' => 'repeat_by_month',
                'value' => 'month',
                'checked' => $this->form_validation->set_value('repeat_by', $booking->repeat_by) == ''
                    || $this->form_validation->set_value('repeat_by', $booking->repeat_by) == 'month'
            );
            $data['repeat_by_week'] = array(
                'name' => 'repeat_by',
                'id' => 'repeat_by_week',
                'value' => 'week',
                'checked' => $this->form_validation->set_value('repeat_by', $booking->repeat_by) != ''
                    && $this->form_validation->set_value('repeat_by', $booking->repeat_by) == 'week'
            );

            $data['repeat_ends_never'] = array(
                'name' => 'repeat_ends',
                'id' => 'repeat_ends_never',
                'value' => 'never',
                'checked' => $this->form_validation->set_value('repeat_ends', $booking->repeat_ends) == ''
                    || $this->form_validation->set_value('repeat_ends', $booking->repeat_ends) == 'never'
            );
            $data['repeat_ends_on'] = array(
                'name' => 'repeat_ends',
                'id' => 'repeat_ends_on',
                'value' => 'on',
                'checked' => $this->form_validation->set_value('repeat_ends', $booking->repeat_ends) == 'on'
            );
            $repeat_ends_on_date = $booking->repeat_ends_on != NULL ? date('d/m/Y', $booking->repeat_ends_on) : NULL;
            $data['repeat_ends_on_date'] = array(
                'name' => 'repeat_ends_on_date',
                'id' => 'repeat_ends_on_date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('repeat_ends_on_date', $repeat_ends_on_date),
                'class' => 'datepicker input-small'
            );
            $data['repeat_ends_after'] = array(
                'name' => 'repeat_ends',
                'id' => 'repeat_ends_after',
                'value' => 'after',
                'checked' => $this->form_validation->set_value('repeat_ends', $booking->repeat_ends) == 'after'
            );
            $data['repeat_ends_after_occurences'] = array(
                'name' => 'repeat_ends_after_occurences',
                'id' => 'repeat_ends_after_occurences',
                'type' => 'text',
                'class' => 'input-small',
                'value' => $this->form_validation->set_value('repeat_ends_after_occurences', $booking->repeat_ends_after)
            );
            $data['booking_id'] = array(
                'booking_id' => $booking->id,
            );
            $data['save'] = array(
                'name' => 'save',
                'content' => lang('book_save_button'),
                'class' => 'btn btn-primary',
                'type' => 'submit'
            );
            $data['cancel'] = array(
                'name' => 'cancel',
                'content' => lang('book_cancel_button'),
                'class' => 'btn',
                'type' => 'submit'
            );
            $data['delete'] = array(
                'name' => 'delete',
                'content' => lang('book_delete_button'),
                'class' => 'btn btn-danger',
                'type' => 'submit'
            );

            $this->render_page('bookings/form', $data);
        }
    }

    public function approval()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        if(isset($_POST['approve']) || isset($_POST['decline'])) {
            //if were posting back and cancel was clicked
            if(isset($_POST['approve'])) {
                $booking_id = $this->input->post('approve', TRUE);
                $booking = $this->bookings_model->get_booking($booking_id);

                $message = $this->bookings_model->approve($booking_id);
                $method = 'approve';                
            }

            //if were posting back and cancel was clicked
            if(isset($_POST['decline'])) {
                $booking_id = $this->input->post('decline', TRUE);
                $booking = $this->bookings_model->get_booking($booking_id);//Booking Object ( [id] => 15563 [user] => User Object ( [id] => 85 [username] => seamus mcardle [email] => seamusmcardle491@btinternet.com [first_name] => Seamus [last_name] => McArdle [phone] => 01667456045 [address_1] => Newlands [address_2] => 14 Chattan Gardens [address_3] => [city] => NAIRN [postcode] => IV12 4QP [curling_club] => Nairn Men [mailing_list] => 0 ) [title] => S McArlde Nairn Park Cup [session] => 85 [session_str] => 85 [sheet] => A [start_time] => : [end_time] => : [team_name_1] => [team_name_2] => [score_1] => [score_2] => [provisional] => 1 [paid] => 0 [invoiced] => 0 [start_date] => 1516924800 [repeat_year] => [repeat_month] => [repeat_week_im] => [repeat_weekday] => [repeat_month_interval] => [repeat_interval] => [repeat_end] => [repeat_meta_id] => [repeat] => [repeat_every] => [repeat_by] => [repeat_on] => [repeat_ends] => [repeat_ends_after] => [repeat_ends_on] => [link] => http://127.0.0.1/codeupdate/index.php/bookings/edit/15563 [link_class] => booked-provisional [link_text] => S McArlde Nairn Park Cup: - : )
                $message = $this->cancel($booking);//message [message] => Booking was deleted successfully
                $method = 'decline';                
            }

            if(is_null($booking_id) || $booking_id == '') {
                show_404();
            }

            if(!empty($booking)) {
                $this->load->library('Parser');
                $this->load->library('Email');

                $this->email->from(
                    $this->config->item('admin_email', 'ion_auth'),
                    $this->config->item('admin_name', 'ion_auth')
                );
                $this->email->to($booking->user->email);
                $this->email->subject(lang('booking_' . $method . '_email_subject'));

                $message = $this->parser->parse_string(
                    lang('booking_' . $method . '_email_message'),
                    array(
                        'title' => $booking->title,
                        'date' => date('d/m/Y', $booking->start_date),
                        'time' => $booking->start_time
                    ),
                    TRUE
                );
                $this->email->message($message);
                $this->email->send();
            }
            $this->session->set_flashdata($message['type'], $message['message']);

            redirect($this->uri->uri_string(), 'refresh');
        }

        //get unapproved bookings
        $data['bookings'] = $this->bookings_model->get_bookings_for_approval();
        $data['message'] = $this->session->flashdata('message');
        $data['error'] = $this->session->flashdata('error');

        $this->render_page('Bookings/approval', $data);
    }

    public function auto_complete()
    {
        $field = $this->uri->segment(3, true);
        $query = $this->input->get('query');
        $available_fields = array('title', 'team_name');
        $double_fields = array('team_name');

        if($field == NULL || $field == '' || !in_array($field, $available_fields)) {
            return array();
        }

        $data = $this->bookings_model->get_auto_complete($field, $query, in_array($field, $double_fields));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function cancel($booking)
    {
        $message = $this->bookings_model->delete($booking->id);

        if($message['type'] != 'error' && $booking->start_date > time()) {
            $this->load->library('Parser');
            $this->load->library('Email');

            $users = $this->ion_auth_model->get_mailing_list();

            foreach($users as $user) {
                $this->email->clear();
                $this->email->to($user->email);
                $this->email->from(
                    $this->config->item('admin_email', 'ion_auth'),
                    $this->config->item('admin_name', 'ion_auth')
                );
                $this->email->subject(lang('booking_cancellation_email_subject'));

                $email_message = $this->parser->parse_string(
                    lang('booking_cancellation_email_message'),
                    array(
                        'first_name' => $user->first_name,
                        'date' => date('d/m/Y', $booking->start_date),
                        'start_time' => $booking->start_time,
                        'end_time' => $booking->end_time,
                        'unsubscribe' => site_url('auth/edit_user/' . $user->id)
                    ),
                    TRUE
                );

                $this->email->message($email_message);

                $this->email->send();
            }
        }

        return $message;
    }

    public function delete($booking_id)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        if(is_null($booking_id)) {
            show_404();
        }

        $booking = $this->bookings_model->get_booking($booking_id);
        $message = $this->cancel($booking);

        $this->session->set_flashdata($message['type'], $message['message']);

        redirect('/', 'refresh');
    }

    public function edit()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        //send to booking function for shard code
        $this->add();
    }

    private  function notify_admin($booking)
    {
        if(!is_null($booking)) {
            $this->load->library('Parser');
            $this->load->library('Email');

            $admins = $this->ion_auth_model->get_admin_users();

            $email_message = $this->parser->parse_string(
                lang('booking_notify_admin_email_message'),
                array(
                    'user' => $booking['user']->first_name . ' ' . $booking['user']->last_name,
                    'date' => date('d/m/Y', $booking['date']),
                    'start_time' => $booking['start_time'],
                    'end_time' => $booking['end_time'],
                    'link' => site_url('bookings/approval')
                ),
                TRUE
            );

            foreach($admins as $admin) {
                $this->email->clear();
                $this->email->to($admin->email);
                $this->email->from(
                    $this->config->item('admin_email', 'ion_auth'),
                    $this->config->item('admin_name', 'ion_auth')
                );
                $this->email->subject(lang('booking_notify_admin_email_subject'));
                $this->email->message($email_message);

                $this->email->send();
            }
        }

        return TRUE;
    }
}