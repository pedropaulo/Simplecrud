<?php

echo form_open_multipart($url . '/' . $action . '/' . $this->uri->segment(4));

foreach ($fields_name as $field) {

    if ($fields_data[$field]['primary_key'] == 0) {
        $data['name'] = $field;
        $data['value'] = $fields_line_data[$field];
        $data['data'] = $fields_data[$field];
        $data['label'] = $fields_label_name[$field];
        $this->load->view('scrud/' . $template . '/field_input', $data);
    }
}

echo form_submit('mysubmit', 'Submit Post!');
echo form_close();
