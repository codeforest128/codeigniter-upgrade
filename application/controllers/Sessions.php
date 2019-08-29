<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Id_Base.php');

class Sessions extends Ice_diary_base
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Sessions_model','sessions_model');
        $this->load->helper('url');
        $this->load->helper('ice_diary');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        if(isset($_POST['edit']) || isset($_POST['delete'])) {
            //if were posting back and edit was clicked
            if(isset($_POST['edit'])) {
                $session_id = $this->input->post('edit', TRUE);
                $method = 'edit';
            }

            //if were posting back and decline was clicked
            if(isset($_POST['delete'])) {
                $session_id = $this->input->post('delete', TRUE);
                $method = 'delete';
            }

            if(is_null($session_id) || $session_id == '') {
                show_404();
            }

            $message = $this->sessions_model->$method($session_id);

            $this->session->set_flashdata($message['type'], $message['message']);

            redirect($this->uri->uri_string(), 'refresh');
        }

        $data['sessions'] = $this->sessions_model->get_custom_sessions();
        $data['message'] = $this->session->flashdata('message');
        $data['error'] = $this->session->flashdata('error');

        $this->render_page('Sessions/index', $data);
    }

    public function add($session_id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        if(!is_null($session_id)) {
            $session = $this->sessions_model->get_session($session_id);
        }
        else {
            require_once(__DIR__ . '/../libraries/POCOs/Session.php');
            $session = new Session();
        }

        //if were posting back and delete was clicked
        if(isset($_POST['delete']) && $this->input->post('id') != '') {
            $message = $this->sessions_model->delete($this->input->post('id', TRUE));

            $uri = site_url('sessions');

            if($message['type'] == 'error') {
                $uri = $this->uri->uri_string();
            }

            $this->session->set_flashdata($message['type'], $message['message']);
            redirect($uri, 'refresh');
        }

        //if were posting back and cancel was clicked
        if(isset($_POST['cancel'])) {
            redirect('sessions');
        }

        // validate form input
        $this->form_validation->set_rules('date', $this->lang->line('session_validation_date_label'), 'required|xss_clean|callback_valid_date');
        $this->form_validation->set_rules('start_time_hours', $this->lang->line('session_validation_start_time_hours_label'), 'required|xss_clean');
        $this->form_validation->set_rules('start_time_mins', $this->lang->line('session_validation_start_time_mins_label'), 'required|xss_clean');
        $this->form_validation->set_rules('end_time_hours', $this->lang->line('session_validation_end_time_hours_label'), 'required|xss_clean');
        $this->form_validation->set_rules('end_time_mins', $this->lang->line('session_validation_end_time_mins_label'), 'required|xss_clean');
        $this->form_validation->set_rules('session_number', $this->lang->line('session_validation_session_number_label'), 'required|xss_clean');

        $data['error'] = '';
        $data['message'] = '';

        if ($this->form_validation->run() == true) {
            $start_date = $this->input->post('date', TRUE) . ' ' . $this->input->post('start_time_hours', TRUE) . ':' . $this->input->post('start_time_mins', TRUE);
            $start_date = datetime_to_mysql($start_date);
            $end_date = $this->input->post('date', TRUE) . ' ' . $this->input->post('end_time_hours', TRUE) . ':' . $this->input->post('end_time_mins', TRUE);
            $end_date = datetime_to_mysql($end_date);

            $insert['session_start'] = $start_date;
            $insert['session_end'] = $end_date;
            $insert['session_number'] = $this->input->post('session_number', TRUE);

            $date = date('Y-m-d', date_to_unix($this->input->post('date', TRUE)));
            $conflicts = $this->sessions_model->check_for_conflicts($date, $insert['session_number'], $session_id);
            if($conflicts > 0) {
                $data['error'] = lang('session_duplicate_found');
            }
        }

        if ($this->form_validation->run() == true && $data['error'] == '') {

            //if we are updating a pre-existing booking
            if($this->input->post('id') != '') {
                $session_id = $this->input->post('id');
                $message = $this->sessions_model->edit($insert, $session_id);
            }
            else {
                $message = $this->sessions_model->add($insert);
            }

            if($message['type'] == 'error') {
                $uri = $this->uri->uri_string();
            }
            else {
                $uri = site_url('sessions');
            }

            $this->session->set_flashdata($message['type'], $message['message']);
            redirect($uri, 'refresh');
        }
        else {
            $hours_options = create_hour_array();
            $minute_options = create_minute_array();

            if($session->start_time != '') {
                $start_time = explode(':', $session->start_time);
                $start_time_hours = $start_time[0];
                $start_time_mins = $start_time[1];
            }
            else {
                $start_time_hours = '00';
                $start_time_mins = '00';
            }

            if($session->end_time != '') {
                $end_time = explode(':', $session->end_time);
                $end_time_hours = $end_time[0];
                $end_time_mins = $end_time[1];
            }
            else {
                $end_time_hours = '00';
                $end_time_mins = '00';
            }

            $data['date'] = array(
                'name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date', $session->date),
                'class' => 'datepicker'
            );
            $data['start_time_hours'] = array(
                'name' => 'start_time_hours',
                'attr' => 'id="start_time_hours" class="span2"',
                'options' => $hours_options,
                'value' => $this->form_validation->set_value('start_time_hours', $start_time_hours)
            );
            $data['start_time_mins'] = array(
                'name' => 'start_time_mins',
                'attr' => 'id="start_time_mins" class="span2"',
                'options' => $minute_options,
                'value' => $this->form_validation->set_value('start_time_mins', $start_time_mins)
            );
            $data['end_time_hours'] = array(
                'name' => 'end_time_hours',
                'attr' => 'id="end_time_hours" class="span2"',
                'options' => $hours_options,
                'value' => $this->form_validation->set_value('end_time_hours', $end_time_hours)
            );
            $data['end_time_mins'] = array(
                'name' => 'end_time_mins',
                'attr' => 'id="end_time_mins" class="span2"',
                'options' => $minute_options,
                'value' => $this->form_validation->set_value('end_time_mins', $end_time_mins)
            );
            $data['session_number'] = array(
                'name' => 'session_number',
                'attr' => 'id="session_number" size="6"',
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6'
                ),
                'value' => $this->form_validation->set_value('session_number', $session->session_number)
            );
            $data['id'] = array(
                'id' => $session_id,
            );
            $data['save'] = array(
                'name' => 'save',
                'content' => lang('session_save_button'),
                'class' => 'btn btn-primary',
                'type' => 'submit'
            );
            $data['cancel'] = array(
                'name' => 'cancel',
                'content' => lang('session_cancel_button'),
                'class' => 'btn',
                'type' => 'submit'
            );
            $data['delete'] = array(
                'name' => 'delete',
                'content' => lang('session_delete_button'),
                'class' => 'btn btn-danger',
                'type' => 'submit'
            );
        }

        $data['session'] = $session;
        $data['message'] .= $this->session->flashdata('message');
        $data['error'] .= validation_errors() . $this->session->flashdata('error');

        $this->render_page('Sessions/form', $data);
    }

    public function edit($session_id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        $this->add($session_id);
    }
}