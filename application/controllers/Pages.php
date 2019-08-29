<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Id_Base.php');

class Pages extends Ice_diary_base {

    public function view($page = 'home')
    {
        if ( ! file_exists(APPPATH . 'views/Pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->render_page('Pages/' . $page, $data);
    }
}