<?php

// includes
///////////////////////////////////

function get_version3 ($entry){
        
        global $app;
		$tam=strlen($app) +1;
		$version = substr($entry, $tam);
		return $version;

}

//////////////////////////////////////////////////////////////////////////////
$smella=array();


// uma versao inteira
function  scanVersion($path, $version, $exclude){
	global $smella;

	$smella=null;
	unset($smella);
	$smella=array();

    $dir = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
    //$files = new RecursiveIteratorIterator($dir,RecursiveIteratorIterator::SELF_FIRST);
    $filter   = new MyDirFilter($dir);
    $files   = new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($files as $file) {

        $ext = pathinfo($file, PATHINFO_EXTENSION) ;
        if ($ext=='html')
            parse_html_file($file);
        else if ($ext=='twig' || $ext=='tpl' || $ext=='mustache')
            parse_template_file($file);
        else if ($ext=='php') 
            parse_php_file($file);
        else if ($ext=='js') 
            parse_js_file($file);

    }
	
	print "\n$path $version \n"; //, tam:" . sizeof($smella) . "\n";
	//print "$path $version , tam:" . sizeof($smella);
    // write
	//print "\n------------------------";
	
    writeArrayCS();
	
	
	/*foreach ($smella as $i => $value) {
		unset($smella[$i]);
	}*/

}





/******************* php and html ************************* */ 

function parse_html_file($file){
    //print "HTML: $file\n";
    parse_file_html_common($file);
    
}

function parse_template_file($file){
    //print "HTML: $file\n";
    parse_file_html_common($file);
    
}


function parse_php_file($file){
    //print "PHP: $file\n";
    parse_file_html_common($file);
    
}


function parse_file_html_common($file){
    
    // get dom
    $dom = file_get_html($file);
    // get js
	///////////////$matches=array();

    $type="client";
    $smell="embed JS";

    $matches = $dom->find('//script[not(@src)]');
    test_for($matches, $file, $type, $smell);
    
	//print_r($matches);
	//print sizeof($matches) . " ";
	unset ($matches);




    // inline js, onlick onmouseover etc
    $smell="inline JS";
    $matches = $dom->find('//*/@*[starts-with(name(), "on")]');
    test_for($matches, $file, $type, $smell);
    unset ($matches);


    //get css
    $smell="embed CSS";
    $matches = $dom->find('style');
    test_for($matches, $file, $type, $smell);
    unset ($matches);


    /// inline css
    $smell="inline CSS";
    $matches = $dom->find('*[style]');
    test_for($matches, $file, $type, $smell);
    unset ($matches);

    $dom->clear(); 
    unset($dom);

}

function test_for($matches, $file, $type, $smell){
    if ($matches){
        foreach ($matches as $match){
            set_ccode_smells($file, $type, $smell, $match);
        }
    }

}


/******************* js ************************* */ 
function parse_js_file($file){
    //print "JS: $file\n";

    // css
    // check .style.
    // not .style because its a change to classes
    $type="client";
    $smell="css in JS";

    $lines = file($file);
    $tam = sizeof($lines);
    
    for($i=0; $i< $tam ;$i++){
        if (strpos($lines[$i], ".style.")){
            $line= $i + 1;  
            set_ccode_smells_line($file, $type, $smell, $line);
        }
            
    }
    
    $smell="css in JS: jquery";
    //jquery 
    for($i=0; $i< $tam ;$i++){
        if (strpos($lines[$i], ".css(")){
            $line= $i + 1;  
            set_ccode_smells_line($file, $type, $smell, $line);
        }
        
    }

    // falta html em javascrip (processar dom)    
    // será que é um smell ?????

}


// para php- funcao que se se ha php e html


/////////////////////////////////////////////////////////// 
function set_ccode_smells($file, $type, $smell, $match){
    global $smella;
    $where=$match->node->parentNode->localName;
    $line= $match->node->getLineNo();

    $smella[]=["file"=> $file ,"type"=> $type, "smell"=> $smell, 'where' => $where, "line"=> $line];

    //print "$file : $type $smell @ $where  line :  $line\n";
	
	print sizeof($smella) . " ";
}

function set_ccode_smells_line($file, $type, $smell, $line){
    global $smella;

    $smella[]=["file"=> $file ,"type"=> $type, "smell"=> $smell, 'where' => "", "line"=> $line];

    //print "$file : $type $smell  line :  $line\n";
	print sizeof($smella) . " ";
}


?>