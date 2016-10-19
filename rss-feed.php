<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/newsgroup.php');
if(!HAS_NEWS){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');


$rf=new ResFile(ABSPATH.'cnf/resources.txt');
$ng=new NewsGroup();
header("Content-type: text/xml; charset=".$rf->GetValue('news-rss','encoding',$lang));
echo $ng->GetItemsByIdCliRSS('news/rss.html', 'a', 0, $lang);
?>