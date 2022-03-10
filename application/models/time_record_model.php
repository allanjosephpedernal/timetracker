<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Time_Record_Model extends CI_Model {
    // table
    public $table = 'employees';
    public $time_record = 'time_records';

    public $first_name;
    public $last_name;
    public $created_by;
    public $datetime_added;
    public $datetime_updated;

    // datatables
    public $column_order = array(null, 'first_name','last_name','created_by','datetime_added','datetime_updated');
    public $column_search = array('first_name','last_name','created_by','datetime_added','datetime_updated');
    public $order = array('id' => 'asc');

    public function getTimeRecord()
    {
        $query = $this->db->get($this->table, 10);
        return $query->result();
    }

    public function insert_entry($data = array())
    {
        // count data
        if(COUNT($data))
        {
            $this->db->insert($this->time_record, $data);
            return $this->db->insert_id();
        }

        return 0;
    }

    public function update_entry($id = 0, $data = array())
    {
        if(! empty($id) && COUNT($data))
        {
            $this->db->update($this->time_record, $data, array('id' => $id));
            return $id;
        }

        return 0;
    }

    public function getLog($employee_id = 0)
    {
        if(! empty($employee_id))
        {
            $this->db->where('employee_id',$employee_id);
            $this->db->where('date_added',Carbon::now()->format('Y-m-d'));
            $this->db->order_by('id','DESC');
            $this->db->limit(1);
            return $this->db->get($this->time_record)->row();
        }

        return;
    }

    /*
    *------------
    * DATATABLES
    *------------
    */

    public function get_datatables($start, $length, $search, $order)
    {
        $this->_get_datatables_query($search, $order);
        if($length != -1)
        {
            $this->db->select('
                employees.id,
                CONCAT_WS(" ", first_name, last_name) AS employee, 
                (SELECT user_name FROM users WHERE users.id = (SELECT user_id FROM time_records WHERE time_records.employee_id = employees.id ORDER BY ID DESC LIMIT 1)) AS user, 
                (SELECT date_added FROM time_records WHERE time_records.employee_id = employees.id ORDER BY time_records.date_added DESC LIMIT 1) AS date_added,
                (SELECT time_in FROM time_records WHERE time_records.employee_id = employees.id ORDER BY time_records.date_added DESC LIMIT 1) AS time_in,
                (SELECT time_out FROM time_records WHERE time_records.employee_id = employees.id ORDER BY time_records.date_added DESC LIMIT 1) AS time_out,
            ');
            $this->db->having('date_added',Carbon::now()->format('Y-m-d'));
            $this->db->limit($length, $start);
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function count_filtered($search, $order)
    {
        $this->_get_datatables_query($search, $order);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /*
    *------------
    * PRIVATE
    *------------
    */

    private function _get_datatables_query($search, $order)
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item)
        {
            if($search['value'])
            {
                if($i===0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $search['value']);
                }
                else
                {
                    $this->db->or_like($item, $search['value']);
                }
 
                if(count($this->column_search) - 1 == $i)
                {
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($order))
        {
            $this->db->order_by($this->column_order[$order['0']['column']], $order['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}