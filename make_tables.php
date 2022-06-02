<?php

//$apps=["dokuwiki", "phpmyadmin" , "opencart", "phpbb", "mediawiki" , "prestashop" , "phppgadmin", "vanilla"];
$apps=["dolibarr", "roundcubemail" , "openemr", "kanboard"];

$db= new mysqli('localhost', 'root', '', 'serversmells');


foreach ($apps as $app){
  $s1="CREATE TABLE IF NOT EXISTS `" . $app . "_client_smells` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `version` varchar(20) NOT NULL,
    `file` varchar(512) NOT NULL,
    `type` varchar(20) NOT NULL,
    `smell` varchar(20) NOT NULL,
    `tag` varchar(20) NOT NULL,
    `line` int(11) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


  create($s1);
}







function create($sql){
	global $db;
	
	if ($db->query($sql) === TRUE) {
		echo "Table created successfully";
	} else {
		echo "Error creating table: " . $db->error;
	}
	echo "\n";
}

$db->close();
echo "create tables for $app done";