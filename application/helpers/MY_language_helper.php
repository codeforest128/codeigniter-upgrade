<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */
if ( ! function_exists('lang'))
{
    function lang($line, $id = '', $class = '')
    {
        $CI =& get_instance();
        $line = $CI->lang->line($line);

        if ($id != '')
        {
            $class = $class != '' ? ' class= "' . $class . '"' : '';
            $line = '<label for="'.$id.'"'.$class.'>'.$line."</label>";
        }

        return $line;
    }
}