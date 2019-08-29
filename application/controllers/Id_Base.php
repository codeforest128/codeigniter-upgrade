<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ice_diary_base
 */
class Ice_diary_base extends CI_Controller
{
    /**
     * @var
     */
    protected $current_user;

    /**
     * @var bool
     */
    protected $is_logged;

    /**
     * @var
     */
    protected $is_admin;

    /**
     * @var
     */
    protected $approval_count;

    /**
     *
     */
    function __construct()
    {
        parent::__construct();

        // if caching is enabled in config, turn it on with the specified caching
        if($this->config->item('caching')) {
            $this->output->cache($this->config->item('caching_duration'));
        }

        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('Ion_Auth');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '<br />');

        $this->load->helper('language');
        $this->lang->load('auth', 'english');
        $this->lang->load('diary', 'english');

        if ($this->ion_auth->logged_in())
        {
            $this->current_user = $this->ion_auth->user()->row();
            $this->is_logged = TRUE;
            $this->is_admin = $this->ion_auth->is_admin();
        }
        else {
            $this->is_logged = FALSE;
            $this->is_admin = FALSE;
        }

        $this->load->model('Bookings_model', 'bookings_model');

        if($this->is_admin)
        {
            $this->approval_count = $this->bookings_model->get_count_bookings_for_approval();
        }
    }

    /**
     * @param $view
     * @param $data
     * @param bool $as_data
     */
    protected function render_page($view, $data)
    {
        $data['is_logged'] = $this->is_logged;
        $data['is_admin'] = $this->is_admin;
        $data['current_user'] = $this->current_user;
        $data['approval_count'] = $this->approval_count;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * @param $view
     * @param $data
     * @param bool $as_data
     */
    protected function render_page_for_pdf($view, $data)
    {
        $data['hide_static'] = TRUE;

        $html = '';

        $html .= $this->load->view('templates/header', $data, TRUE);
        $html .= $this->load->view($view, $data, TRUE);
        $html .= $this->load->view('templates/footer', $data, TRUE);

        return $html;
    }


    /**
     * @param $str
     * @param $field
     * @return bool
     * form validation callback to check if one field is specified, is another required?
     */
    public function is_required($str, $field)
    {
        $field_str = str_replace('_', ' ', $field);
        $message = str_replace('{field}', $field_str, $this->lang->line('custom_validation_is_valid'));

        if ( ! isset($_POST[$field])) {
            $this->form_validation->set_message('is_required', $message);
            return FALSE;
        }

        $field = $_POST[$field];

        if($this->form_validation->required($field)) {
            $is_empty = $this->form_validation->required($str);

            if(!$is_empty) {
                $this->form_validation->set_message('is_required', $message);
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * @param $date
     * @return bool
     * form validation callback to check to see if a field is a valid uk date format
     */
    public function valid_date($date)
    {
        //check date is in correct format before processing
        $date_regex = '/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](19|20)\d\d/';

        if($date != '' && !preg_match($date_regex, $date)) {
            $this->form_validation->set_message('valid_date', $this->lang->line('custom_validation_valid_date'));
            return FALSE;
        }

        return TRUE;
    }

    public function repeat_ends_occurences($value, $repeat_ends)
    {
        if($this->input->post($repeat_ends) == 'after' && $value == '') {
            $this->form_validation->set_message('repeat_ends_occurences', $this->lang->line('custom_validation_repeat_ends_occurences'));
            return FALSE;
        }

        return TRUE;
    }

    public function repeat_ends_date($value, $repeat_ends)
    {
        if($this->input->post($repeat_ends) == 'on' && $value == '') {
            $this->form_validation->set_message('repeat_ends_date', $this->lang->line('custom_validation_repeat_ends_date'));
            return FALSE;
        }

        if(!$this->valid_date($value)) {
            $this->form_validation->set_message('repeat_ends_date', $this->lang->line('custom_validation_valid_date'));
            return FALSE;
        }

        return TRUE;
    }
}