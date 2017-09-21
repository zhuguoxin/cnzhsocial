<?php
/*
* Load one of the site map, list all the article.
*/
if(!isset($_GET['xmlurl'])){
    echo '<br/>请给出sitemap url地址。';
}else{
    echo '<br/>sitemap url： ' . $_GET["xmlurl"];
    $input_xmlsitemap_url = $_GET["xmlurl"];
    $xmlDoc = new DOMDocument();
    $xmlDoc->load("$input_xmlsitemap_url");
    echo $xmlDoc->saveXML();
}



