<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Employee extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model');
    }
    
	public function index()
	{
        $data['custom_scripts'] = ['/assets/js/employee.js'];
		$this->load->template('employee',$data);
	}

    public function store()
    {
        try
        {
			// validate
			$this->form_validation->set_rules('employee_first_name', 'First Name', 'required');
			$this->form_validation->set_rules('employee_last_name', 'Last Name', 'required');

			// get input
			$first_name = $this->input->post('employee_first_name');
			$last_name = $this->input->post('employee_last_name');

			// check form
			if ($this->form_validation->run() == FALSE)
			{
				// not validated
                $this->responseWithError(['success' => false, 'message' => validation_errors()]);
				exit;
			}

            // set data
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'created_by' => $this->session->user_id,
                'datetime_added' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            // new employee id
            $employee_id = $this->employee_model->insert_entry($data);

            // success
            $this->responseWithSuccess(['success' => true, 'employee_id' => $employee_id]);
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
            // get employee
            $employee = $this->employee_model->find_entry(['id' => $id]);
            if(! empty($employee))
            {
                // success
                $this->responseWithSuccess(['success' => true, 'employee' => $employee]);
                exit;
            }
            else
            {
                // employee dont exists
                $this->responseWithError(['success' => false, 'message' => 'Employee dont exists!']);
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
            $this->form_validation->set_rules('employee_id', 'Employee ID', 'required');
			$this->form_validation->set_rules('employee_first_name', 'First Name', 'required');
            $this->form_validation->set_rules('employee_last_name', 'Last Name', 'required');

			// get input
            $employee_id = $this->input->post('employee_id');
			$first_name = $this->input->post('employee_first_name');
			$last_name = $this->input->post('employee_last_name');

			// check form
			if ($this->form_validation->run() == FALSE)
			{
				// not validated
                $this->responseWithError(['success' => false, 'message' => validation_errors()]);
                exit;
			}

            // set data
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'datetime_updated' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            // employee id
            $employee_id = $this->employee_model->update_entry($employee_id, $data);

            // success
            $this->responseWithSuccess(['success' => true, 'employee_id' => $employee_id]);
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
            $employee_id = $this->employee_model->delete_entry($id);

            // success
            $this->responseWithSuccess(['success' => true, 'employee_id' => $id]);
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
            $this->employee_model->bulk_delete($ids);

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
        $list = $this->employee_model->get_datatables($start, $length, $search, $order);
        $data = array();
        $no = $start;
        foreach ($list as $employees) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="chk" name="chk[]" value="'.$employees->id.'">';
            $row[] = $employees->first_name;
            $row[] = $employees->last_name;
            $row[] = $employees->created_by;
            $row[] = Carbon::parse($employees->datetime_added)->format('m/d/Y');
            $row[] = Carbon::parse($employees->datetime_updated)->format('m/d/Y');
            $row[] = '
                <a href="#" rel="'.$employees->id.'" class="btn btn-xs btn-primary show_qr"><i class="icon-fixed-width icon-qrcode"></i> QR Code</a>
                <a href="#" rel="'.$employees->id.'" class="btn btn-xs btn-warning edit_employee"><i class="icon-fixed-width icon-edit"></i> Edit</a>
                <a href="#" rel="'.$employees->id.'" class="btn btn-xs btn-danger delete_employee"><i class="icon-fixed-width icon-trash"></i> Delete</a>
            ';
 
            $data[] = $row;
        }
        
        // construct output
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->employee_model->count_all(),
            "recordsFiltered" => $this->employee_model->count_filtered($search, $order),
            "data" => $data
        );

        // success
        $this->responseWithSuccess($output);
        exit;
    }
}
