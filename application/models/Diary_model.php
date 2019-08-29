<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Diary_model
 */
class Diary_model extends CI_Model
{
    /**
     *
     */
    public function __construct()
    {
        $this->load->database();
        $this->load->library('Poco_Factory','poco_factory');
    }
}