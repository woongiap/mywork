<?php session_start(); 
require_once('inc/view.inc.php');
show_header('Klasz - Frquently Asked Questions');
echo <<<leftbox
<style type="text/css">
//span { border: 1px solid #000; }
</style>
<div id="left-box" style="width:24%;color:#C0C0C0;font-size:18pt;font-weight:bold;margin-top:10px;">
<div><span style="color:red;font-size:64pt;">F</span>REQUENTLY</div>
<div><span style="color:green;font-size:64pt;">A</span>SKED</div>
<div><span style="color:blue;font-size:64pt;">Q</span>UESTIONS</div>
</div>
leftbox;
echo '<div id="middle-box" style="width:74%">';
require_once('mod/collab.inc.php');
$faqs = faq_get();
$i = 1;
foreach ($faqs as $faq) {
	echo '<div id="q'.$i.'" class="faq-q">'.$i.'. '.$faq->q.'</div>';
	echo '<div id="a'.$i.'" class="faq-a">'.$faq->a.'</div>';
	$i++;
}
echo '</div>';
show_footer();
?>
