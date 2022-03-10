<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate extends MY_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index()
	{
		// redirect
        if($this->session->is_logged_in){
            redirect('/dashboard');    
        }

		$this->load->view('login');
	}

	public function login()
	{
		try
		{
			// validate
			$this->form_validation->set_rules('user_name', 'Username', 'required');
			$this->form_validation->set_rules('user_password', 'Password', 'required');

			// get input
			$user_name = $this->input->post('user_name');
			$user_password = $this->input->post('user_password');

			// check form
			if ($this->form_validation->run() == FALSE)
			{
				// not validated
                $this->responseWithError(['success' => false, 'message' => validation_errors()]);
				exit;
			}
			
			// get user
			$user = $this->user_model->find_entry(['user_name' => $user_name]);
			if(! empty($user))
			{
				// check if valid
				$valid = password_verify($user_password, $user->user_password);
				if($valid)
				{
					// set login
					$this->session->set_userdata([
						'user_id' => $user->id,
						'user_name' => $user->user_name,
						'is_logged_in' => TRUE
					]);

					// success
					$this->responseWithSuccess(['success' => true]);
					exit;
				}

				// invalid password
                $this->responseWithError(['success' => false, 'message' => 'Invalid password!']);
                exit;
			}

			// invalid username
			$this->responseWithError(['success' => false, 'message' => 'Invalid username!']);
			exit;
		}
		catch(Exception $e)
		{
			// error
			$this->output
			->set_status_header(400)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(['success' => false, 'message' => $e->getMessage(), 'csrf' => $csrf], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit;
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
