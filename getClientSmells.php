<?php

$db= new mysqli('localhost', 'root', '', 'serversmells');

$dircsv = "csv/$app" . "/";
if (!is_dir($dircsv)) mkdir($dircsv);

$dir="../code/$app/";

//print "$dir\n";

class MyDirFilter extends RecursiveFilterIterator {
    public function accept() {
        global $exclude;
        //$excludePath = array('exclude_dir1', 'exclude_dir2');
        $excludea= explode("," , $exclude);
        foreach($excludea as $exPath){
            if(strpos($this->current()->getPath(), $exPath) !== false){
                return false;
            }
        }
        return true;

    }
}

if ($handle = opendir($dir)) {

	$i=0;

    while (false !== ($entry = readdir($handle))) {
        //$dir . $entry;
        //print "$entry\n";
        
        if ($entry != '..' && $entry != '.' && file_exists($dir . $entry)){

            if (! strstr(strtolower($entry), $app)) continue;

            $version=get_version3($entry);

            print $i++ . " - $entry -  $version" . PHP_EOL; 
			
			///////////////////////if ($i==6) break;
            
            ///////////////////////////////////////////
            $path = $dir . $entry;

            //open file
            $filename = $dircsv. "/" . $entry .".csv";
            $fp = fopen($filename,"w");
            $sep=";";
            $csv=['file', 'type', 'smell', 'where', 'line'];
            fputcsv($fp, $csv, $sep);

            scanVersion($path, $version, $exclude);  /// alll

            //close file
            fclose($fp);
	
        }
    }
    closedir($handle);
} 


//close db
$db->close();