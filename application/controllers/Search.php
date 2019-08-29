<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Id_Base.php');

class Search extends Ice_diary_base
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('ice_diary');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Auth/login/' . $this->uri->uri_string(), 'refresh');
        }

        $this->form_validation->set_rules('title', $this->lang->line('search_validation_date_label'), 'xss_clean');
        $this->form_validation->set_rules('start_date', $this->lang->line('session_validation_sdate_label'), 'xss_clean|callback_valid_date');
        $this->form_validation->set_rules('end_date', $this->lang->line('search_validation_edate_label'), 'xss_clean|callback_is_required[start_date]|callback_valid_date');
        $this->form_validation->set_rules('start_time_hours', $this->lang->line('session_validation_start_time_hours_label'), 'xss_clean');
        $this->form_validation->set_rules('start_time_mins', $this->lang->line('session_validation_start_time_mins_label'), 'xss_clean');
        $this->form_validation->set_rules('end_time_hours', $this->lang->line('session_validation_end_time_hours_label'), 'xss_clean');
        $this->form_validation->set_rules('end_time_mins', $this->lang->line('session_validation_end_time_mins_label'), 'xss_clean');
        $this->form_validation->set_rules('session_number', $this->lang->line('session_validation_session_number_label'), 'xss_clean');
        $this->form_validation->set_rules('sheet', $this->lang->line('session_validation_sheet_label'), 'xss_clean');
        $this->form_validation->set_rules('team', $this->lang->line('session_validation_team_label'), 'xss_clean');
        $this->form_validation->set_rules('provisional', $this->lang->line('session_validation_provisional_label'), 'xss_clean');
        $this->form_validation->set_rules('paid', $this->lang->line('session_validation_paid_label'), 'xss_clean');
        $this->form_validation->set_rules('invoiced', $this->lang->line('session_validation_invoiced_label'), 'xss_clean');

        $data['bookings'] = array();

        if ($this->form_validation->run() == true) {
            $all_terms['title'] = $this->input->post('title', TRUE);
            $all_terms['start_date'] = $this->input->post('start_date', TRUE);
            $all_terms['end_date'] = $this->input->post('end_date', TRUE);
            if($this->input->post('start_time_hours') != '') {
                $all_terms['start_time'] = $this->input->post('start_time_hours', TRUE);
                $all_terms['start_time'] .= $this->input->post('start_time_mins') != '' ? ':' . $this->input->post('start_time_mins', TRUE) : ':00';
            }
            if($this->input->post('end_time_hours') != '') {
                $all_terms['end_time'] = $this->input->post('end_time_hours', TRUE);
                $all_terms['end_time'] .= $this->input->post('end_time_mins') != '' ? ':' . $this->input->post('end_time_mins', TRUE) : ':00';
            }
            $all_terms['session_id'] = $this->input->post('session_number', TRUE);
            $all_terms['sheet'] = $this->input->post('sheet', TRUE);
            $all_terms['team'] = $this->input->post('team', TRUE);
            $all_terms['provisional'] = $this->input->post('provisional', TRUE);
            $all_terms['paid'] = $this->input->post('paid', TRUE);
            $all_terms['invoiced'] = $this->input->post('invoiced', TRUE);

            $terms = array();

            foreach($all_terms as $input => $value) {
                if($value != '' && $value) {
                    $terms[$input] = $value;
                }
            }

            $data['bookings'] = $this->bookings_model->search_for_bookings($terms);

            //if were posting back and cancel was clicked
            if(isset($_POST['pdf'])) {
                $this->load->helper(array('dompdf', 'file'));

                $data['terms_str'] = '';
                foreach($terms as $input => $value) {
                    $data['terms_str'] .= $input . ' = ' . $value . ', ';
                }
                $data['terms_str'] = substr($data['terms_str'], 0 , -2);

                // page info here, db calls, etc.
                $html = $this->render_page_for_pdf('search/pdf_results', $data);

                $pdf_data = pdf_create($html, '', FALSE);

                $this->output
                    ->set_content_type('application/pdf')
                    ->set_output($pdf_data);
            }

            //if were posting back and cancel was clicked
            if(isset($_POST['csv'])) {
                $this->load->library('parser');

                $terms_str = '';
                foreach($terms as $input => $value) {
                    $terms_str .= $input . '-' . $value . '_';
                }
                $terms_str = substr($terms_str, 0 , -1);

                $filename = $this->parser->parse_string(
                    lang('search_csv_filename'),
                    array(
                        'date' => date('d-m-Y-H-i'),
                        'terms' => $terms_str,
                    ),
                    TRUE
                );

                header('Content-disposition: attachment; filename=' . $filename);
                header('Content-type: text/csv');

                $handle = fopen('php://output', 'w');

                fputcsv($handle, array(
                    lang('search_pdf_title')
                ));

                fputcsv($handle, array(
                    lang('search_generated_on'),
                    date('d/m/Y H:i')
                ));

                fputcsv($handle, array(
                    lang('search_terms'),
                    $terms_str
                ));

                fputcsv($handle, array(
                    ' '
                ));

                fputcsv($handle, array(
                    'Title',
                    'Start Date',
                    'Time',
                    'Sessions',
                    'Sheet',
                    'Status',
                    'Paid',
                ));

                foreach ($data['bookings'] as $booking) {
                    fputcsv($handle, array(
                        $booking->title,
                        date('d/M/Y', $booking->start_date),
                        $booking->start_time . ' - ' . $booking->end_time,
                        $booking->session_str,
                        $booking->sheet,
                        $booking->provisional == 0 ? lang('search_results_confirmed') : lang('search_results_provisional'),
                        $booking->paid == 1 ? lang('search_results_paid') : $booking->invoiced == 1 ? lang('search_results_invoiced') : lang('search_results_no_payment_or_invoice')
                    ));
                }

                fclose($handle);
                exit;
            }

            //if were posting back and cancel was clicked
            if(isset($_POST['print'])) {

                $data['terms_str'] = '';
                foreach($terms as $input => $value) {
                    $data['terms_str'] .= $input . ' = ' . $value . ', ';
                }
                $data['terms_str'] = substr($data['terms_str'], 0 , -2);

                $data['print'] = true;

                // page info here, db calls, etc.
                $html = $this->render_page_for_pdf('search/pdf_results', $data);

                echo $html;
                return;
            }
        }

        $hours_options = create_hour_array($required = FALSE);
        $minute_options = create_minute_array($required = FALSE);

        $data['title'] = array(
            'name' => 'title',
            'id' => 'title',
            'type' => 'text',
            'data-provide' => 'typeahead',
            'data-field' => 'title',
            'autocomplete' => 'off',
            'class' => 'typeahead',
            'value' => $this->form_validation->set_value('title')
        );
        $data['start_date'] = array(
            'name' => 'start_date',
            'id' => 'start_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('start_date'),
            'class' => 'datepicker'
        );
        $data['end_date'] = array(
            'name' => 'end_date',
            'id' => 'end_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('end_date'),
            'class' => 'datepicker'
        );
        $data['start_time_hours'] = array(
            'name' => 'start_time_hours',
            'attr' => 'id="start_time_hours" class="span2"',
            'options' => $hours_options,
            'value' => $this->form_validation->set_value('start_time_hours')
        );
        $data['start_time_mins'] = array(
            'name' => 'start_time_mins',
            'attr' => 'id="start_time_mins" class="span2"',
            'options' => $minute_options,
            'value' => $this->form_validation->set_value('start_time_mins')
        );
        $data['end_time_hours'] = array(
            'name' => 'end_time_hours',
            'attr' => 'id="end_time_hours" class="span2"',
            'options' => $hours_options,
            'value' => $this->form_validation->set_value('end_time_hours')
        );
        $data['end_time_mins'] = array(
            'name' => 'end_time_mins',
            'attr' => 'id="end_time_mins" class="span2"',
            'options' => $minute_options,
            'value' => $this->form_validation->set_value('end_time_mins')
        );
        $data['session_number'] = array(
            'name' => 'session_number',
            'attr' => 'id="session_number"',
            'options' => array(
                '' => 'Select a session',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6'
            ),
            'value' => $this->form_validation->set_value('session_number')
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
            'value' => $this->form_validation->set_value('sheet')
        );
        $data['team'] = array(
            'name' => 'team',
            'id' => 'team',
            'type' => 'text',
            'data-provide' => 'typeahead',
            'data-field' => 'team_name',
            'autocomplete' => 'off',
            'class' => 'typeahead',
            'value' => $this->form_validation->set_value('team')
        );
        $data['provisional'] = array(
            'name' => 'provisional',
            'id' => 'provisional',
            'value' => '1',
            'checked' => !is_null($this->form_validation->set_value('provisional'))
            && $this->form_validation->set_value('provisional') != ''
            && $this->form_validation->set_value('provisional') == '1'
        );
        $data['paid'] = array(
            'name' => 'paid',
            'id' => 'paid',
            'value' => '1',
            'checked' => !is_null($this->form_validation->set_value('paid'))
            && $this->form_validation->set_value('paid') != ''
            && $this->form_validation->set_value('paid') == '1'
        );
        $data['invoiced'] = array(
            'name' => 'invoiced',
            'id' => 'invoiced',
            'value' => '1',
            'checked' => !is_null($this->form_validation->set_value('invoiced'))
            && $this->form_validation->set_value('invoiced') != ''
            && $this->form_validation->set_value('invoiced') == '1'
        );
        $data['search'] = array(
            'name' => 'search',
            'content' => lang('search_button'),
            'class' => 'btn btn-primary',
            'type' => 'submit'
        );
        $data['pdf'] = array(
            'name' => 'pdf',
            'content' => lang('search_pdf'),
            'class' => 'btn btn-pdf',
            'type' => 'submit'
        );
        $data['csv'] = array(
            'name' => 'csv',
            'content' => lang('search_csv'),
            'class' => 'btn',
            'type' => 'submit'
        );
        $data['print'] = array(
            'name' => 'print',
            'content' => lang('search_print'),
            'class' => 'btn btn-print',
            'type' => 'submit'
        );
        $data['search_again'] = array(
            'name' => 'search_again',
            'content' => lang('search_again'),
            'class' => 'btn',
            'type' => 'submit'
        );

        $data['message'] = $this->session->flashdata('message');
        $data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->render_page('Search/index', $data);
    }
}