<?php

class Simplecrud_model extends CI_Model {

    public $table;
    public $primary_key;

    public function __construct() {
        parent::__construct();
    }

    public function get_fields() {
        $fields = $this->db->list_fields($this->table);
        return $fields;
    }

    public function get_table_data() {

        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function get_line_data($id = 0) {
        $this->db->where($this->primary_key, $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function insert($data) {

        return $this->db->insert($this->table, $data);
    }

    public function update($data, $id) {

        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {

        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    public function get_fields_data() {

        $fields = $this->db->field_data($this->table);

        $data = FALSE;

        foreach ($fields as $field) {

            if ($field->primary_key == TRUE) {
                $this->primary_key = $field->name;
            }

            $data[$field->name]['type'] = $field->type;
            $data[$field->name]['max_length'] = $field->max_length;
            $data[$field->name]['primary_key'] = $field->primary_key;
        }
        return $data;
    }

}