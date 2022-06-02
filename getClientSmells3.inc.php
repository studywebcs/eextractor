<?php
///////////////////////////////////////////////////////////////////////////////////////////  writes

/// corre uma vez na versao
function writeArrayCS(){
    global $smella, $app;

    //print "----------------\n";
    //print_r($smella);
	
	
    foreach ($smella as $smellv){

        $relpath="..\\code\\" . $app . "\\";
        
        $smellv['file'] = substr($smellv['file'] , strlen($relpath) ,  
        strlen($smellv['file']) - strlen($relpath)   );

        ///////print $smellv['file'] . "\n";
		//print "tam smella" . sizeof($smella) . "tam smellv" . sizeof($smellv) . "\n";
        
		//write csv/
        write_line_csv($smellv);
        // write database
        write_line_db($smellv);
    }
	
	//unset ($smella);
	
}



function write_line_csv($smellv){
    global $fp, $sep;
    $csv=$smellv;
    fputcsv($fp, $csv, $sep);
}

function write_line_db($smellv){
    global $db, $app, $version;


    $file2 = $db->real_escape_string($smellv['file']);
    $type = $smellv['type'];
    $smell = $smellv['smell'];
    $tag = $smellv['where'];
    $line = $smellv['line'];


    $sql="insert into " . $app . "_client_smells 
    (version, file, type, smell, tag, line )
    values ('$version', '$file2', '$type', '$smell', '$tag', '$line' )";

    $db->query($sql);

}
