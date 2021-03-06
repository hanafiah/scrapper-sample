<?php
/**
 * composer autoload
 */
require 'vendor/autoload.php';

/**
 * we use symfony crawler library to handle dom handler
 * http://symfony.com/doc/current/components/dom_crawler.html
 */
use Symfony\Component\DomCrawler\Crawler;

/**
 * Your search term
 */
$term = 'school';

/**
 * enable this if you want to get search term from query string
 */
//$term = isset($_GET['s'])?$_GET['s']:'school';
/* 
 * usage
 * index.php?s=school
 * 
 */

/**
 * set the language
 * eng-indmay => English to Indonesian/Malaysian
 * 
 * get full list of languages from this site http://www.kamus.com/
 */
$language = 'eng-indmay';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.kamus.com/$language/$term");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$html = curl_exec($ch);
curl_close($ch);

$crawler = new Crawler();
$crawler->addContent($html);

$content_node=NULL;
$crawler->filter('.resulttable > tr')->each(function (Crawler $node, $i) {
    global $content_node;
    
    if($node->attr('class') == 'subsectionheader' && $node->text()=='English>Malay'){
        $content_node = $i+1; 
    }
    
    if(isset($content_node) && ($content_node==$i)){
        echo $node->filter('td')->eq(2)->text().PHP_EOL;
    }
});
