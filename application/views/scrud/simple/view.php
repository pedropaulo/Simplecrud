
    <?php
    foreach ($fields_name as $field) {       
        $data['name'] = $field;
        $data['value'] = $fields_line_data[$field];
        $data['label'] = $fields_label_name[$field];
        $this->load->view('scrud/' . $template . '/field_info',$data);
    }