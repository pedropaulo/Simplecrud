<?php


echo form_label($label . ": ", $name);

$data = array(
        'name'          => $name,
        'id'            => $name,
        'value'         => $value,
        'maxlength'     => $data['max_length'],
        'size'          => $data['max_length']
);

echo form_input($data);
echo "<br>";
