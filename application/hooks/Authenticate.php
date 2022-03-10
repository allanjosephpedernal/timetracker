<?php
class Authenticate{
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }
    public function check_user_login(){
        if(! $this->CI->session->is_logged_in){
            redirect('/');    
        }
    }
}
?>