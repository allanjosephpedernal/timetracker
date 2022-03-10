<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_Model extends CI_Model {
    // table
    public $table = 'employees';

    public $first_name;
    public $last_name;
    public $created_by;
    public $datetime_added;
    public $datetime_updated;

    // datatables
    public $column_order = array(null, 'first_name','last_name','created_by','datetime_added','datetime_updated');
    public $column_search = array('first_name','last_name','created_by','datetime_added','datetime_updated');
    public $order = array('id' => 'asc');

    public function getEmployees()
    {
        $query = $this->db->get($this->table, 10);
        return $query->result();
    }

    public function find_entry($where = array())
    {
        if(COUNT($where))
        {
            $this->db->where($where);
            return $this->db->get($this->table)->row();
        }

        return;
    }

    public function insert_entry($data = array())
    {
        // count data
        if(COUNT($data))
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        return 0;
    }

    public function update_entry($id = 0, $data = array())
    {
        if(! empty($id) && COUNT($data))
        {
            $this->db->update($this->table, $data, array('id' => $id));
            return $id;
        }

        return 0;
    }

    public function delete_entry($id = 0)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $id;
    }

    public function bulk_delete($ids = array())
    {
        if(COUNT($ids))
        {
            foreach($ids AS $id)
            {
                $this->delete_entry($id);
            }

            return $ids;
        }

        return [];
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
            $this->db->select('employees.id, employees.first_name, employees.last_name, (SELECT user_name FROM users WHERE id = employees.created_by) AS created_by, employees.datetime_added, employees.datetime_updated');
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