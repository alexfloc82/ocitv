<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset: utf-8');
header('Content-Disposition: attachment; filename=Tareas.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Tarea', 'Semestre','Cadena', 'Comprobado','Analizado','Analista','Revisado','Revisor','Edicion'));

$json_data =    file_get_contents('https://api.mlab.com/api/1/databases/ocitv/collections/ValuePairs?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T');
$values = json_decode($json_data);

$json_data = file_get_contents('https://api.mlab.com/api/1/databases/ocitv/collections/piezas?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T');
$obj = json_decode($json_data);

foreach ($obj as $item) {
        $flatData = array();
        try{array_push($flatData, $values[0]->semestre->{$item->semestre});}catch(exception $e){array_push($flatData, "");}
        if(isset($item->id_tarea)){array_push($flatData, $item->id_tarea);}else{array_push($flatData, "");}
        if(isset($item->semestre)){array_push($flatData, $values[0]->semestre->{$item->semestre});}else{array_push($flatData, "");}
        if  (isset($item->cadena)){array_push($flatData, $values[0]->cadena->{$item->cadena});}else{array_push($flatData, "");}
        //if(isset($item->date)){array_push($flatData, $fecha[0]);}else{array_push($flatData, "");}
        if(isset($item->comprobado)){array_push($flatData, $item->comprobado);}else{array_push($flatData, "");}
        if(isset($item->analizado)){array_push($flatData, $item->analizado);}else{array_push($flatData, "0");}
        if(isset($item->analista)){array_push($flatData, $item->analista);}else{array_push($flatData, "");}
        if(isset($item->revisado)){array_push($flatData, $item->revisado);}else{array_push($flatData, "0");}
        if(isset($item->revisor)){array_push($flatData, $item->revisor);}else{array_push($flatData, "");}
        if(isset($item->edicion)){array_push($flatData, $values[0]->edicion->{$item->edicion});}else{array_push($flatData, "");}
        fputcsv($output, $flatData);
}

// fetch the data

//$rows = mysql_query('SELECT field1,field2,field3 FROM table');

// loop over the rows, outputting them
//while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

fclose($output);
?>
