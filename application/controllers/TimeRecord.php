<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class TimeRecord extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('time_record_model');
    }
    
	public function index()
	{
        $data['custom_scripts'] = [
            'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js',
            '/assets/js/timerecord.js'
        ];
		$this->load->template('time_record',$data);
	}

    public function log($employee_id)
    {
        try
        {
            // check time record
            $time_record = $this->time_record_model->getLog($employee_id);
            if(! empty($time_record))
            {
                // get time record
                $date_added = $time_record->date_added;
                $time_in = $time_record->time_in;
                $time_out = $time_record->time_out;

                // create new time record
                if(! empty($date_added) && ! empty($time_in) && ! empty($time_out))
                {
                    // new time record
                    $data = [
                        'employee_id' => $employee_id,
                        'user_id' => $this->session->user_id,
                        'date_added' => Carbon::now()->format('Y-m-d'),
                        'time_in' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                    $this->time_record_model->insert_entry($data);
                }
                else
                {
                    // update time record
                    $data = [
                        'time_out' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                    $this->time_record_model->update_entry($time_record->id, $data);
                }
                
            }
            else
            {
                // new time record
                $data = [
                    'employee_id' => $employee_id,
                    'user_id' => $this->session->user_id,
                    'date_added' => Carbon::now()->format('Y-m-d'),
                    'time_in' => Carbon::now()->format('Y-m-d H:i:s')
                ];
                $this->time_record_model->insert_entry($data);
            }

            // get time record
            $time_record = $this->time_record_model->getLog($employee_id);
            $time_record->date_added = Carbon::parse($time_record->date_added)->format('m/d/Y');
            $time_record->time_in = Carbon::parse($time_record->time_in)->format('g:i:s A');
            $time_record->time_out = $time_record->time_out ? Carbon::parse($time_record->time_out)->format('g:i:s A') : NULL;

            // success
            $this->responseWithSuccess(['success' => true, 'employee_id' => $employee_id, 'time_record' => $time_record]);
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
        $list = $this->time_record_model->get_datatables($start, $length, $search, $order);
        $data = array();
        $no = $start;
        foreach ($list as $time_records) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $time_records->employee;
            $row[] = $time_records->user;
            $row[] = Carbon::parse($time_records->date_added)->format('m/d/Y');
            $row[] = Carbon::parse($time_records->time_in)->format('g:i:s A');
            $row[] = $time_records->time_out ? Carbon::parse($time_records->time_out)->format('g:i:s A') : '';
 
            $data[] = $row;
        }
        
        // construct output
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->time_record_model->count_all(),
            "recordsFiltered" => $this->time_record_model->count_filtered($search, $order),
            "data" => $data
        );

        // success
        $this->responseWithSuccess($output);
        exit;
    }
}
