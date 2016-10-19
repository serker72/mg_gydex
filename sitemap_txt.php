<?

require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/resoursefile.php');
require_once('classes/sitemap.php');



//вывод из шаблона

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
   
$tree=new SiteMap();
 
 
header('Content-type: text/plain'); 
 
$tree->DrawTreeCli('sitemap.html', $alls);
 
// var_dump($alls);
foreach($alls as $v){
?>
<? //=str_replace(SITEURL, '', $v['url'])?>

<?
}?>