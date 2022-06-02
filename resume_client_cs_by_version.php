<?php

// $app="phpmyadmin";
// $app="opencart";
// $app="phpbb";
// $app="dokuwiki";
$app="mediawiki";
// $app="prestashop";
// $app="phppgadmin";
// $app="vanilla";
// $app="dolibarr";
// $app="roundcubemail";
// $app="openemr";
// $app="kanboard";

define("SEP", ",");

$db= new mysqli('localhost', 'root', '', 'serversmells');

//-------------------------------
$sql="select * from ". $app ."_version order by id";

//print $sql;

print "--== $app ==--";

/*
embed JS
inline JS
inline CSS
embed CSS
css in JS
css in JS: jquery*/

$result= $db->query($sql);

$time_init = time();

$top="Id,Version,Date, 
embed JS,
inline JS,
embed CSS,
inline CSS,
css in JS,
css in JS: jquery";

$top = str_replace(array("\r", "\n"), '', $top);

$fp = fopen("resume/resume_" . $app . ".csv", 'x');
fwrite($fp, $top."\r\n");
$line="";

while ($row =  $result->fetch_assoc()){
	
	$version=$row['version'];
	$id=$row['id'];
	$date=$row['date'];

	print "\n $id $version";
	
	$line = get_smells_version($id, $version, $date);

	print $line;
	
	fwrite($fp, $line."\r\n");
//	$lines .=$line ."\r\n";

	$seconds = time() - $time_init;

	print "\nElapsed: ". floor($seconds / 3600) . gmdate(":i:s", $seconds % 3600);

	
}
//file_put_contents("resume/resume_" . $app . ".csv", $top . "\r\n" . $lines);
fclose($fp);

function get_smells_version(int $idversion, string $version, string $date){

	global $db, $app;
	
	$cs=array();
	$line="";
	
	$cs['embed JS'] = 0 ;
	$cs['inline JS'] = 0 ;
	$cs['embed CSS'] = 0 ;
	$cs['inline CSS'] = 0 ;
	$cs['css in JS'] = 0 ;
	$cs['css in JS: jquery'] = 0 ;


	$sql="select * from ". $app ."_client_smells where version='$version' order by id";

	$result= $db->query($sql);

	while ($row =  $result->fetch_assoc()){

		$rule=$row['smell'];	
		
		$cs[$rule] = $cs[$rule] + 1;
		
	}
		
		
	//escrever	
	$line= $idversion . SEP . $version . SEP .$date;

	$line.= SEP . $cs['embed JS'] ;
	$line.= SEP . $cs['inline JS'];
	$line.= SEP . $cs['embed CSS'];
	$line.= SEP . $cs['inline CSS'];
	$line.= SEP . $cs['css in JS'];
	$line.= SEP . $cs['css in JS: jquery'];

	return $line;
}