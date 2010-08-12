<?php
require_once('mod/global.inc.php');
global $g_state_map;
global $g_category_map;	

$q = isset($_GET['q'])?$_GET['q']:(isset($_SESSION['q'])?$_SESSION['q']:'');
$s = isset($_GET['s'])?$_GET['s']:(isset($_SESSION['s'])?$_SESSION['s']:'');
$c = isset($_GET['c'])?$_GET['c']:(isset($_SESSION['c'])?$_SESSION['c']:'');
$_SESSION['q'] = $q;
$_SESSION['c'] = $c;
echo <<<one
<div id="search-box">
	<div style="float:left;"><a href="home.php"><img class="logo" src="img/logo.png"></a></div>
	<form style="margin-left:30px;float:left;width:800px;" name="search_form" method="get" action="search.php">
		<div style="float:left;margin: 20px 2px 0px 2px;">
			<div><input type="text" name="q" id="q" size="33" value="$q"/></div>
			<div id="terms" class="suggestion">
				access<br/>banana<br/>cinema<br/>dirty<br/>
			</div>
		</div>
		<div style="float:left;margin: 20px 2px 0px 2px;">
				<select name="c" id="c">
one;
					foreach ($g_category_map as $key=>$value) {
						if ($c == $key) {
							echo '<option value="'.$key.'" selected="selected">'.htmlspecialchars($value[0]).'</option>';
						} else {
							echo '<option value="'.$key.'">'.htmlspecialchars($value[0]).'</option>';
						}						
					}				
echo <<<two
				</select>
		</div>
		<div style="float:left;margin: 20px 2px 0px 2px;">
				<select name="s" id="s">
two;
					foreach ($g_state_map as $key=>$value) {
						if ($s===$key) {
							echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$value.'</option>';
						}						
					}
echo <<<three
				</select>
		</div>
		
		<div style="float:left;margin: 20px 2px 0px 2px;">
			<input class="button" style="font-weight:bold;" type="submit" value="Search Classifieds" />
		</div>
	</form>

	<div class="clear"></div>
</div>
three;
?>