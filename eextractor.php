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

$app="mediawiki";
$exclude="docs,cache,vendor,images,languages,tests,js_embed";


// $app="prestashop";
// $exclude="cache,docs, loc,localization,tests,tests-legacy,translations,travis-scripts,upload,var,vendor,log,js_embed";

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
include "getClientSmells2.inc.php";
include "getClientSmells3.inc.php";

//main program
include "getClientSmells.php";