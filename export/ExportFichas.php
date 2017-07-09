<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset: utf-8');
header('Content-Disposition: attachment; filename=Tareas.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Internal_ID','Tarea', 'Semestre','Cadena','Fecha', 'Comprobado','Analizado','Analista','Revisado','Revisor','Edicion',
        'titulo_Ficha','terminado','Hora_comienzo','min_comienzo','s_cominenzo','hora_fin','min_fin','s_fin','formato','genero',
        'ambito','valor_ambito','espana_conjunto','zona',
        'fuente','fuente_directa','directa','generica','confidencial','declaraciones','hombre','mujer',
        'categoria_tematica',
        'valor_editorial','informador','aparece','equilibrio','contexto','relacion_cadena'));

$json_data =    file_get_contents('https://api.mlab.com/api/1/databases/ocitv/collections/ValuePairs?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T');
$values = json_decode($json_data);

$json_data = str_replace('$','S',file_get_contents('https://api.mlab.com/api/1/databases/ocitv/collections/piezas?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T'));
$obj = json_decode($json_data);

foreach ($obj as $item) {
        $flatData = array();
        if(isset($item->_id->Soid)){array_push($flatData, $item->_id->Soid);}else{array_push($flatData, "");}
        if(isset($item->id_tarea)){array_push($flatData, $item->id_tarea);}else{array_push($flatData, "");}
        if(isset($item->semestre)){array_push($flatData, $values[0]->semestre->{$item->semestre});}else{array_push($flatData, "");}
        if(isset($item->cadena)){array_push($flatData, $values[0]->cadena->{$item->cadena});}else{array_push($flatData, "");}
        if(isset($item->date->Sdate)){array_push($flatData, $item->date->Sdate);}else{array_push($flatData, "");}
        if(isset($item->comprobado)){array_push($flatData, $item->comprobado);}else{array_push($flatData, "");}
        if(isset($item->analizado)){array_push($flatData, $item->analizado);}else{array_push($flatData, "0");}
        if(isset($item->analista)){array_push($flatData, $item->analista);}else{array_push($flatData, "");}
        if(isset($item->revisado)){array_push($flatData, $item->revisado);}else{array_push($flatData, "0");}
        if(isset($item->revisor)){array_push($flatData, $item->revisor);}else{array_push($flatData, "");}
        if(isset($item->edicion)){array_push($flatData, $values[0]->edicion->{$item->edicion});}else{array_push($flatData, "");}
        fputcsv($output, $flatData);
        
        //datos de las fichas
        foreach ($item->fichas as $ficha){
            $flatDataFicha = array();
            $flatDataFicha = $flatData;
            if(isset($ficha->title)){array_push($flatDataFicha, $ficha->title);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->terminado)){array_push($flatDataFicha, $ficha->terminado);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->bhour)){array_push($flatDataFicha, $ficha->bhour);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->bmin)){array_push($flatDataFicha, $ficha->bmin);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->bsec)){array_push($flatDataFicha, $ficha->bsec);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->ehour)){array_push($flatDataFicha, $ficha->ehour);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->emin)){array_push($flatDataFicha, $ficha->emin);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->esec)){array_push($flatDataFicha, $ficha->esec);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->format)){array_push($flatDataFicha, $values[0]->format->{$ficha->format});}else{array_push($flatDataFicha, "");}
            if(isset($ficha->genero)){array_push($flatDataFicha, $values[0]->genero->{$ficha->genero});}else{array_push($flatDataFicha, "");}
            // localización
            if(isset($ficha->ambito)){array_push($flatDataFicha, $values[0]->ambito->{$ficha->ambito});}else{array_push($flatDataFicha, "");}
            if(isset($ficha->valorAmbito)){array_push($flatDataFicha, $ficha->valorAmbito);}else{array_push($flatDataFicha, "");}
            if(isset($ficha->espConjunto)){array_push($flatDataFicha, $ficha->espConjunto);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->zona)){array_push($flatDataFicha, $values[0]->zona->{$ficha->zona});}else{array_push($flatDataFicha, "");}             
            // Fuentes
            if(isset($ficha->fuente)){array_push($flatDataFicha, $ficha->fuente);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->fDirecta)){array_push($flatDataFicha, $ficha->fDirecta);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->fDir)){array_push($flatDataFicha, $ficha->fDir);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->fGenerica)){array_push($flatDataFicha, $ficha->fGenerica);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->fConfidencial)){array_push($flatDataFicha, $ficha->fConfidencial);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->declaraciones)){array_push($flatDataFicha, $ficha->declaraciones);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->declhombre)){array_push($flatDataFicha, $ficha->declhombre);}else{array_push($flatDataFicha, "0");}
            if(isset($ficha->declmujeres)){array_push($flatDataFicha, $ficha->declmujeres);}else{array_push($flatDataFicha, "0");}
            // Quien habla
            // De Quien se habla
            // De que se habala
            if(isset($item->catTem)){array_push($flatData, $values[0]->catTem->{$item->catTem});}else{array_push($flatData, "");}
            // Tratamiento
            if(isset($item->valedit)){array_push($flatData, $values[0]->valedit->{$item->valedit});}else{array_push($flatData, "");}
            if(isset($item->informador)){array_push($flatData, $item->informador);}else{array_push($flatData, "");}
            if(isset($item->infoAparece)){array_push($flatData, $item->infoAparece);}else{array_push($flatData, "0");}
            if(isset($item->equilibrio)){array_push($flatData, $values[0]->equilibrio->{$item->equilibrio});}else{array_push($flatData, "");}
            if(isset($item->context)){array_push($flatData, $values[0]->context->{$item->context});}else{array_push($flatData, "");}
            if(isset($item->relcad)){array_push($flatData, $values[0]->relcad->{$item->relcad});}else{array_push($flatData, "");}
            
            /*foreach ($ficha->local as $local){
                $flatDataFicha = array();
                $flatDataFicha = $flatData;
            }*/
            
            
            fputcsv($output, $flatDataFicha);
        }
}

fclose($output);
?>