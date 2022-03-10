<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class User extends MY_Controller {
    
	public function __construct()
    {
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index()
	{
        $data['custom_scripts'] = ['/assets/js/user.js'];
		$this->load->template('user',$data);
	}

    public function store()
    {
        try
        {
			// validate
			$this->form_validation->set_rules('user_name', 'Username', 'required|is_unique[users.user_name]');
			$this->form_validation->set_rules('user_password', 'Password', 'required');
            $this->form_validation->set_rules('user_type', 'Type', 'required');

			// get input
			$user_name = $this->input->post('user_name');
			$user_password = $this->input->post('user_password');
            $user_type = $this->input->post('user_type');

			// check form
			if ($this->form_validation->run() == FALSE)
			{
				// not validated
                $this->responseWithError(['success' => false, 'message' => validation_errors()]);
				exit;
			}

            // set data
            $data = [
                'user_name' => $user_name,
                'user_password' => password_hash($user_password, PASSWORD_BCRYPT),
                'user_type' => $user_type,
                'datetime_added' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            // new user id
            $user_id = $this->user_model->insert_entry($data);

            // success
            $this->responseWithSuccess(['success' => true, 'user_id' => $user_id]);
            exit;
		}
		catch(Exception $e)
		{
            // error
            $this->responseWithError(['success' => false, 'message' => $e->getMessage()], 500);
            exit;
		}
    }

    public function show($id)
    {
        try
        {
            // get user
            $user = $this->user_model->find_entry(['id' => $id]);
            if(! empty($user))
            {
                // success
                $this->responseWithSuccess(['success' => true, 'user' => $user]);
                exit;
            }
            else
            {
				// user dont exists
                $this->responseWithError(['success' => false, 'message' => 'User dont exists!']);
				exit;
            }
        }
        catch(Exception $e)
        {
            // error
            $this->responseWithError(['success' => false, 'message' => $e->getMessage()], 500);
            exit;
        }
    }

    public function update()
    {
        try
        {
			// validate
            $this->form_validation->set_rules('user_id', 'User ID', 'required');
			$this->form_validation->set_rules('user_name', 'Username', 'required');
            $this->form_validation->set_rules('user_type', 'Type', 'required');

			// get input
            $user_id = $this->input->post('user_id');
			$user_name = $this->input->post('user_name');
			$user_password = $this->input->post('user_password');
            $user_type = $this->input->post('user_type');

			// check form
			if ($this->form_validation->run() == FALSE)
			{
				// not validated
                $this->responseWithError(['success' => false, 'message' => validation_errors()]);
				exit;
			}

            // set data
            $data = [
                'user_name' => $user_name,
                'user_type' => $user_type,
                'datetime_modified' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            // append password
            if(! empty($user_password))
            {
                $data['user_password'] = password_hash($user_password, PASSWORD_BCRYPT);
            }

            // new user id
            $user_id = $this->user_model->update_entry($user_id, $data);

            // success
            $this->responseWithSuccess(['success' => true, 'user_id' => $user_id]);
            exit;
		}
		catch(Exception $e)
		{
            // error
            $this->responseWithError(['success' => false, 'message' => $e->getMessage()], 500);
            exit;
		}
    }

    public function delete($id)
    {
        try
        {
            $user_id = $this->user_model->delete_entry($id);

            // success
            $this->responseWithSuccess(['success' => true, 'user_id' => $user_id]);
            exit;
        }
        catch(Exception $e)
        {
            // error
            $this->responseWithError(['success' => false, 'message' => $e->getMessage()], 500);
            exit;
        }
    }

    public function bulk_delete()
    {
        try
        {
            $ids = $this->input->post('ids');
            $this->user_model->bulk_delete($ids);

            // success
            $this->responseWithSuccess(['success' => true, 'ids' => $ids]);
            exit;
        }
        catch(Exception $e)
        {
            // error
            $this->responseWithError(['success' => false, 'message' => $e->getMessage()], 500);
            exit;
        }
    }

    public function datatable()
    {
        // get params
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

        // construct data
        $list = $this->user_model->get_datatables($start, $length, $search, $order);
        $data = array();
        $no = $start;
        foreach ($list as $users) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="chk" name="chk[]" value="'.$users->id.'">';
            $row[] = $users->user_name;
            $row[] = $users->user_type;
            $row[] = Carbon::parse($users->datetime_added)->format('m/d/Y');
            $row[] = Carbon::parse($users->datetime_modified)->format('m/d/Y');
            $row[] = '
                <a href="#" rel="'.$users->id.'" class="btn btn-xs btn-warning edit_user"><i class="icon-fixed-width icon-edit"></i> Edit</a>
                <a href="#" rel="'.$users->id.'" class="btn btn-xs btn-danger delete_user"><i class="icon-fixed-width icon-trash"></i> Delete</a>
            ';
 
            $data[] = $row;
        }
        
        // construct output
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->user_model->count_all(),
            "recordsFiltered" => $this->user_model->count_filtered($search, $order),
            "data" => $data
        );

        // success
        $this->responseWithSuccess($output);
        exit;
    }
}
