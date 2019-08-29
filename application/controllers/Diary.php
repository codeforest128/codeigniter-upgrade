<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Id_Base.php');

class Diary extends Ice_diary_base
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Sessions_model', 'sessions_model');
        $this->load->helper('url');
        $this->load->helper('ice_diary');
    }

    public function index()
    {
        $this->load->helper('date');

        $data['title'] = 'Ice Diary';
        $data['year'] = $this->uri->segment(3) ? $this->uri->segment(3) : date('Y');
        $data['month'] = $this->uri->segment(4) ? $this->uri->segment(4) : date('m');
        $data['day'] = $this->uri->segment(5) ? $this->uri->segment(5) : date('d');
        $data['date'] = $data['year'] . '-' . $data['month'] . '-' . $data['day'];
        $data['unix_date'] = strtotime($data['date']);
        $data['today'] = date('Y-m-d');
        $data['previous'] = date('Y/m/d', strtotime('- 7 days', $data['unix_date']));
        $data['next'] = date('Y/m/d', strtotime('+ 7 days', $data['unix_date']));

        // generate a drop down of months for quick selection
        $months = array();

        for ($i = 1; $i <= 12; $i++)
        {
            $month_date = date('Y') . '/' . $i . '/01';
            $month_date = strtotime($month_date);
            $months[date('m', $month_date)] = date("F", $month_date);
        }

        $data['months'] = array(
            'name' => 'months',
            'attr' => 'id="months" class="span6"',
            'options' => $months,
            'value' => $data['month']
        );

        // generate a drop down of years for quick selection
        $years = array();

        for ($i = 0; $i < 3; $i++)
        {
            $year = (int)date('Y') + $i;
            $years[$year] = $year;
        }

        $data['years'] = array(
            'name' => 'years',
            'attr' => 'id="years" class="span6"',
            'options' => $years,
            'value' => $data['year']
        );

        $data['submit'] = array(
            'name' => 'submit',
            'content' => lang('calendar_submit'),
            'class' => 'btn btn-primary',
            'type' => 'submit'
        );

        $data['is_logged'] = $this->is_logged;
        $data['is_admin'] = $this->is_admin;

        // calculate the start date of the week where the date was selected
        $data['wc'] = x_week_range($data['date']);

        // only update the start date for the grid if the date is specified in the url
        // otherwise use current date
        $data['grid_date'] = $this->uri->segment(5) ? $data['wc'] : x_week_range(date('Y-m-d'));

        $prefs = array (
            'start_day' => 'saturday',
            'month_type' => 'long',
            'day_type' => 'short',
            'start_day' => 'monday',
            'show_next_prev' => TRUE,
            'next_prev_url' => site_url('diary/index'),
            'template' =>
                '{table_open}<table border="0" cellpadding="4" cellspacing="0" class="calendar" width="100%">{/table_open}

                   {heading_row_start}<thead><tr>{/heading_row_start}

                   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
                   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
                   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

                   {heading_row_end}</tr></thead>{/heading_row_end}

                   {week_row_start}<tr>{/week_row_start}
                   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
                   {week_row_end}</tr>{/week_row_end}

                   {cal_row_start}<tr>{/cal_row_start}
                   {cal_cell_start}<td>{/cal_cell_start}

                   {cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
                   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

                   {cal_cell_no_content}<a href="' . site_url('diary/index/' . $data['year'] . '/' . $data['month']) . '/{day}">{day}</a>{/cal_cell_no_content}
                   {cal_cell_no_content_today}<div class="today"><a href="' . site_url('diary/index/' . $data['year'] . '/' . $data['month']) . '/{day}">{day}</a></div>{/cal_cell_no_content_today}

                   {cal_cell_blank}&nbsp;{/cal_cell_blank}

                   {cal_cell_end}</td>{/cal_cell_end}
                   {cal_row_end}</tr>{/cal_row_end}

                   {table_close}</table>{/table_close}'
        );

        $this->load->library('calendar', $prefs);

        $data['calendar'] = $this->calendar->generate($data['year'], $data['month']);

        $data['sessions'] = $this->sessions_model->get_sessions($data['grid_date']);


        $data['bookings'] = $this->bookings_model->get_bookings($data['grid_date']);

        $data['message'] = $this->session->flashdata('message');
        $data['error'] = $this->session->flashdata('error');
        $this->render_page('diary/index', $data);
    }

    public function submit_date()
    {
        $month = $this->input->post('months', TRUE);
        $years = $this->input->post('years', TRUE);

        redirect('diary/index/' . $years . '/' . $month . '/01');
    }
}