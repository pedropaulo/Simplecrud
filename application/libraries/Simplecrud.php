<?php

class Simplecrud {

    protected $CI;
    protected $HTML;
    protected $database_table;
    protected $dbm;
    protected $fields_name;
    protected $fields_label_name;
    protected $fields_table_data;
    protected $fields_data;
    protected $label_field;
    protected $primary_key;
    protected $insert = TRUE;
    protected $actions = array('update', 'delete', 'view');
    protected $current_action;
    protected $url_table;
    protected $template = "simple";

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->library('session');
        $this->CI->load->library('form_validation');
        $this->CI->load->helper('url');
        $this->CI->load->model('simplecrud_model', 'dbm');
    }

    private function init() {
        $this->CI->dbm->table = $this->database_table;
        $this->fields_data = $this->CI->dbm->get_fields_data();
        $this->primary_key = $this->CI->dbm->primary_key;
        $this->define_fields_name();
        $this->define_fields_table_data();
        $this->url_table = $this->CI->router->fetch_class() . '/' . $this->CI->router->fetch_method();

        if (in_array($this->CI->uri->segment(3), $this->actions) || $this->CI->uri->segment(3) == 'insert') {
            $this->current_action = $this->CI->uri->segment(3);
            $this->HTML = $this->mount_action();
        } else {
            $this->HTML = $this->mount_table();
        }

        return $this;
    }

    private function define_fields_name() {

        $this->fields_name = $this->CI->dbm->get_fields();

        foreach ($this->fields_name as $field) {
            if (isset($this->label_field[$field])) {
                $new_data[$field] = $this->label_field[$field];
            } else {
                $new_data[$field] = $field;
            }
        }

        $this->fields_label_name = $new_data;
        return $this;
    }

    private function define_fields_table_data() {
        $this->fields_table_data = $this->CI->dbm->get_table_data();
        return $this;
    }

    private function mount_table() {
        $data['fields_name'] = $this->fields_name;
        $data['fields_label_name'] = $this->fields_label_name;
        $data['fields_table_data'] = $this->fields_table_data;
        $data['insert'] = $this->insert;
        $data['primary_key'] = $this->primary_key;
        $data['actions'] = $this->actions;
        $data['url'] = $this->url_table;

        return $this->CI->load->view("scrud/{$this->template}/table", $data, TRUE);
    }

    private function mount_action() {

        if ($this->CI->input->server('REQUEST_METHOD') === 'POST') {
            $this->do_post();
        }

        $data['fields_name'] = $this->CI->dbm->get_fields();
        $data['fields_line_data'] = $this->CI->dbm->get_line_data($this->CI->uri->segment(4));
        $data['fields_label_name'] = $this->fields_label_name;
        $data['fields_data'] = $this->fields_data;
        $data['primary_key'] = $this->primary_key;
        $data['action'] = $this->current_action;
        $data['url'] = $this->url_table;
        $data['template'] = $this->template;

        if ($this->current_action == 'view') {
            $result = $this->CI->load->view("scrud/{$this->template}/view", $data, TRUE);
        } else {
            $result = $this->CI->load->view("scrud/{$this->template}/form", $data, TRUE);
        }

        return $result;
    }

    private function do_post() {
        foreach ($this->fields_name as $field) {
            if ($field != $this->primary_key) {
                $data[$field] = $this->CI->input->post($field);
            }
        }

        $sucess = $this->do_action($data);

        if ($sucess) {
            redirect($this->url_table);
        }
        return $this;
    }

    private function do_action($data) {
        if ($this->current_action == 'insert') {
            $sucess = $this->CI->dbm->insert($data) == TRUE ? "Informacoes inseridas com sucesso" : FALSE;
        } elseif ($this->current_action == 'update') {
            $sucess = $this->CI->dbm->update($data, $this->CI->uri->segment(4)) == TRUE ? "alteradas" : FALSE;
        } elseif ($this->current_action == 'delete') {
            $sucess = $this->CI->dbm->delete($this->CI->uri->segment(4)) == TRUE ? "excluidas" : FALSE;
        } else {
            $sucess = FALSE;
        }
        return $sucess;
    }

    public function set_label_field($field, $label) {
        $this->label_field[$field] = $label;
    }

    public function set_template($name = 'simple') {
        $this->template = $name;
    }

    public function render($database_table = FALSE) {

        if ($database_table) {
            $this->database_table = $database_table;
            $this->init();
        } else {
            show_error("The database table is not set.");
        }

        return $this->HTML;
    }
}