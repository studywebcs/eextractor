<?php
// $app="phpmyadmin";
// $exclude="doc,examples,locale,sql,vendor,contrib,pmd,js_embed";

// $app="dokuwiki";
// $exclude="doc,examples,locale,sql,vendor,contrib,pmd,tests,test,_test,_cs,js_embed";

// $app="opencart";
// $exclude="doc,examples,locale,sql,vendor,contrib,pmd,tests,js_embed";

// $app="phpbb";
// $exclude="doc,examples,locale,sql,vendor,contrib,pmd,tests,js_embed";



// $app="phppgadmin";
// $exclude="help,images,lang,libraries,xloadtree,js_embed";

// $app="mediawiki";
// $exclude="docs,cache,vendor,images,languages,tests,js_embed";


$app="prestashop";
$exclude="cache,docs, loc,localization,tests,tests-legacy,translations,travis-scripts,upload,var,vendor,log,js_embed,tools";

// $app="vanilla";
// $exclude="cache,confs,vendors,uploads,bin,build,locales,resources,js_embed";



// $app="dolibarr";
// $exclude="locale,langs,vendor,contrib,pmd,tests,test,includes,js_embed";

// $app="roundcubemail";
// $exclude="locale,vendor,contrib,pmd,tests,test,misc,SQL,js_embed";

// $app="openemr";
// $exclude="locale,vendor,contrib,pmd,phpmyadmin,tests,test,misc,js_embed";

// $app="kanboard";
// $exclude="doc,locale,vendor,contrib,pmd,tests,test,misc,js_embed";



print $app. PHP_EOL; 

include "advanced_html_dom.php";

$dir="../code/$app/";

print "$dir\n";

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

            print $i++ . " - $entry" . PHP_EOL; 
            
            ///////////////////////////////////////////
            $path = $dir . $entry;
			
            $dir_embed="$path/js_embed/";

            $files = glob("$dir_embed*");   // apaga
            foreach($files as $file){ 
              if(is_file($file))
                unlink($file); 
            }
            if (is_dir($dir_embed))
                rmdir($dir_embed);

            
            if (!is_dir($dir_embed))
				mkdir($dir_embed);
			
            scanVersionE($path, $exclude);  /// alll
            
        }
    }
    closedir($handle);
} 



function  scanVersionE($path, $exclude){

    


    
    $dir = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
    //$files = new RecursiveIteratorIterator($dir,RecursiveIteratorIterator::SELF_FIRST);
    $filter   = new MyDirFilter($dir);
    $files   = new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION) ;
            if ($ext=='html' || $ext=='php' || $ext=='twig' || $ext=='tpl') 
                parse_file_html_common($file);

    }



}



/******************* php and html ************************* */ 


function parse_file_html_common($file){
    
    // get dom
    $dom = file_get_html($file);
    // get js

    $matches = $dom->find('//script[not(@src)]');
    if ($matches){
        foreach ($matches as $match){
            separate($file, $match);
        }
    }
    unset ($matches);

    $dom->clear(); 
    unset($dom);

}




function separate($file,  $match){
    global $path, $dir_embed, $dir;

    $where=$match->node->parentNode->localName;
    $line= $match->node->getLineNo();

    //print $match->innertext;
    ////$file_name =  str_replace(['/','.','\\'],'_', basename($file));
    $filetemp= substr($file, strlen($path)+1);

    $comment = "$filetemp : @ $where  line :  $line";
    print "$comment\n";

    $file_name =  str_replace(['/','.','\\'],'_', $filetemp);
    $newfile= $dir_embed . $file_name . ".js";

    file_put_contents($newfile, "/* $comment */\n" . $match->text,  FILE_APPEND);

    /*
    extra space, that's a side effect of DomDocument, if you want you can use clean_text method:
    $html->find('tr',0)->clean_text
    and that will squeeze all spaces and trim it.
    */

}

