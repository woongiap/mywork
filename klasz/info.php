<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
	//$("body").append("<img src=\"http://www.google.com/intl/en_ALL/images/logo.gif\"></img>");
});
</script>
<style type="text/css">
div {
	border: 1px solid #000;
	padding: 3px;
}
</style>
</head>
<body>
<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
preg_match('/^a\d*z$/', 'a1534534534534z');
//date_default_timezone_set('Europe/London');
echo date_format(date_create('2008-08-03 14:52:10'), DATE_ATOM).'<br/>';
$datetime = date_create('2008-01-3');
echo '<br/>date:  format:'.date_format($datetime, 'Y-m-d');
echo '_SERVER["QUERY_STRING"]: '.$_SERVER["QUERY_STRING"];

/* test xml
$doc = new DOMDocument('1.0');
$doc->formatOutput = true;	
$root = $doc->createElement('book');
$root = $doc->appendChild($root);	
$title = $doc->createElement('title');
$title = $root->appendChild($title);	
$text = $doc->createTextNode('This is the title');
$text = $title->appendChild($text);
echo '<pre>'.$doc->saveXML().'</pre>';
*/

$q = "<b>&nbsp;&nbsp;&nbsp; must know troubleshooting&nbsp;&nbsp; windows 7,<br>and freebsd and lucene";
echo '<br/>'.html_entity_decode(strip_tags($q),ENT_QUOTES,'utf-8');
echo '<br>hello</br>';
$s = addslashes("kuala lumpur@ this steven's car & thing <b>bold thing</b>");
echo $s;
echo '<br/>';
echo stripslashes($s);
$big_day = mktime(0,0,0,2,2,2010);
$now = mktime();
echo '<br/>COUNT DOWN '.round((($big_day-$now)/(60*60*24)));
$string = '<br/><b>hello&nbsp;,bro&amp;this&nbsp;&lt;cool&gt;</b> becomes hello&nbsp;,bro&amp;this&nbsp;&lt;cool&gt;<span style="font-weight:bold;">span here</span>';
echo '<br/>original: '.$string;
$no_tag = preg_replace('/<[^<>]*>/', ' ', $string);
echo '<br/>no tag: '.$no_tag;
$no_html = html_entity_decode($no_tag,ENT_QUOTES,'utf-8');
echo '<br/>no html: '.$no_html;
$no_extra_space = preg_replace('/[<>]/', ' ', $no_html);
echo '<br/>no extra: '.$no_extra_space;
//phpinfo();
// alter table k_post_history add column ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
//Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.11) Gecko/2009061118 Fedora/3.0.11-1.fc9 Firefox/3.0.11 GTB6
?>
<div style="width:300px;margin:10px auto;" id="klasz">DIV</div>
<?php 
static $terms = array(
' bkt '=>' bukit ',
'tmn'=>'taman',
'jln'=>'jalan',
//'  ', ' ',
'teh'=>'tehteh',
' mee '=>' noodle '
);
$phrase  = "You can        go to JLN taming kiri 2 bkt belimbing to eat pan mee and minum teh and meehoon";

$newphrase = str_ireplace(array_keys($terms), array_values($terms), $phrase);
echo '<p>';
echo $newphrase;
echo '</p>';
echo '<br/>'.$_SERVER["HTTP_USER_AGENT"].'<br/>'.$_SERVER['HTTP_REFERER'].'<br/>'.$_REQUEST["PHPSESSID"];
?>
</body>
</html>
