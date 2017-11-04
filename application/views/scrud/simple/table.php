
<?php
if ($insert) {
    echo anchor($url.'/insert','insert');
}
?>

<table>
    <thead>
        <tr>
            <?php
            foreach ($fields_label_name as $field) {
                echo "<th>{$field}</th>";
            }
            if (count($actions) > 0) {
                echo "<th>actions</th>";
            }
            ?>   
        </tr>    
    </thead>
    <tbody>
        <?php
        
        foreach ($fields_table_data as $fields) {
            echo "<tr>";
            
            $primary = $fields[$primary_key];
            
            foreach ($fields as $field) {
                echo "<td>{$field}</td>";
            }

            if (count($actions) > 0) {
                echo "<td>";
                foreach ($actions as $action) {
                    echo anchor($url.'/' . $action . '/' . $primary ,$action);
                }
                echo "</td>";
            }

            echo "</tr>";
        }
        ?>  
    </tbody>
</table>