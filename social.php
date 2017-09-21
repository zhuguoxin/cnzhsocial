<?php
/*
* 1. Get the article list of CNZH.
* 2. Generate shortened URLs from Sina shorten url service.
* 3. Share articles to social accounts.
* 
* Guoxin Zhu 20170921
*
*/

// load function file
//require('./socialfunctions.php');
$sitemap_string;
$input_xmlsitemap_url = 'http://www.chinesenzherald.co.nz/sitemap.xml';
//$sitemap_string = load_sitemap($input_xmlsitemap_url);
$xmlDoc = new DOMDocument();
echo "Loading ".$input_xmlsitemap_url. "<br /><br />";
$xmlDoc->load("$input_xmlsitemap_url");
$x = $xmlDoc->documentElement;
$nodelist = $x-> getElementsByTagName("loc");
//$node = $this->doc->getElementsByTagName($tag); 
//$latestxml = $nodelist->item($nodelist->length-1);
//echo $xmlDoc->saveXML($latestxml); 
//echo '< a href="'.$latestxml->textContent.'">'.$latestxml->textContent.'</ a>';

for($c = 0; $c<$nodelist->length; $c++){ 
$text[$c] = $nodelist->item($c)->textContent; 
echo '<a href="loadXML.php?xmlurl='.$text[$c].'">'.$text[$c].'</a><br />';

} 
