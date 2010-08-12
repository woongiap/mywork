<?php
require_once('mod/Post.class.php');
require_once('mod/Category.class.php');

function prepare_suggest() {
echo <<<suggest_box
<div id="suggest-box" class="popup" style="width:400px;">
	<div class="popup-title">Suggest a New Category</div>
	<div class="msg-bar" style="display:none;"></div>
	<div style="margin:6px;color:#707070;">In order to *not* confuse our users, we are trying to limit the number of categories
	 to possible minimum. However, we would likely overlook certain important ones. Therefore, we appreciate if 
	 you could tell us the nature of your posting and the category you would like to place it under. 
	 You will be notified as soon as the new category get added, until then, please select 
	 "None of the above" for your posting.</div>
	<form class="form" name="f-sugcat" id="f-sugcat" method="post">
		<label>Your Email<span>(optional)</span></label>
		<div>
		<input type="text" id="suggestor" name="suggestor" />
		<input type="hidden" name="ft" value="sugcat" />
		</div>
		<div>Description of your category
		<textarea style="width:96%;border:1px solid #888;" name="desc" rows="8"></textarea>
		</div>		
		<div style="text-align:right;">
			<span class="link" onclick="close_popup();">Close</span>
			<input type="submit" class="button" name="submit" value="Suggest" />
		</div>		
	</form>
</div>
suggest_box;
}

function show_edit_header($title) {
$yui = <<<hdr
<!-- Individual YUI CSS files -->
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/assets/skins/sam/skin.css" />
<!-- Individual YUI JS files -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/element/element-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/container/container_core-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/menu/menu-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/button/button-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/editor/editor-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/connection/connection-min.js"></script>
<script src="js/jquery.bgiframe.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/k_contribute.min.js?v=1"></script>
hdr;
	show_header($title, $yui);	
}

function show_edit_form($pgtitle, Post $post=NULL, $err='') {
require_once('mod/global.inc.php');
global $g_state_map;
global $g_category_map;	

	$pid = '';
	$title = '';
	$location = '';
	$category_id = '';
	$state_code = '';
	$desc = '';
	if (!empty($post)) {
		$pid = $post->getId();
		$title = htmlspecialchars($post->getTitle());
		$location = htmlspecialchars($post->getLocation());
		$category_id = $post->getCategory()->getId();
		$state_code = $post->getDivcode();
		$desc = htmlentities($post->getDesc(), ENT_QUOTES, 'utf-8'); 
	} else {
		if (isset($_POST['post_title'])) {
			$pid = $_POST['pid'];
			$title = $_POST['post_title'];
			$location = $_POST['location'];
			$desc = $_POST['desc'];
			$category_id = $_POST['category'];
			$state_code = $_POST['state'];
		}
	}
	
echo '<div id="left-box" style="width:74%;" class="section-box round-corner-top yui-skin-sam">';
echo	"<div class=\"section-title\">$pgtitle</div>";
if (!empty($err)) {
	echo '<div class="msg-bar err">'.$err.'</div>';	
}	
echo <<<b1
	<form class="form" name="f-contribute" id="f-contribute" method="post" action="{$_SERVER['PHP_SELF']}" >
		<div class="form-item">
			<label for="post_title">Title<span>(80 characters max)</span></label><span id="msg-title" class="hint"></span>
			<br/><input type="text" id="post_title" name="post_title" size="60" value="$title" />			
		</div>
		<div class="form-item">
			<label for="category">Category</label><span class="hint"></span><span class="link" id="suggest-link">Can't find your category?</span><br/>
				<select name="category" id="category">
b1;
					foreach ($g_category_map as $key=>$value) {
						if ($category_id == $key) {
							echo '<option value="'.$key.'" selected="selected">'.htmlspecialchars($value[0]).'</option>';
						} else {
							if ($key == ANY_CATEGORY) {
								echo '<option value="'.$key.'">--None of the above--</option>';
							} else {
								echo '<option value="'.$key.'">'.htmlspecialchars($value[0]).'</option>';	
							}							
						}						
					}					
echo <<<b2
				</select>			
		</div>
		<div class="form-item clear" style="float:left;">
			<label for="location">Location<span>(e.g. Taman Mutiara Bukit Mertajam)</span></label><span id="msg-location" class="hint"></span>
			<br/><input type="text" id="location" name="location" size="30" value="$location"/>			
		</div>
		<div class="form-item" style="float:left;">
			<label for="state">State</label><span class="hint"></span><br/>
			<select name="state" id="state">
b2;
					foreach ($g_state_map as $key=>$value) {						
						if ($state_code === $key) {
							echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';	
						} else {
							if ($key != ANY_STATE) {
								echo '<option value="'.$key.'">'.$value.'</option>';
							}	
						}						 
					} 
echo <<<b3
			</select>
		 </div> 
		<div class="form-item clear">
			<label for="desc">Description</label><span id="msg-desc" class="hint"></span>
			<textarea name="desc" id="desc" rows="25" cols="62">$desc</textarea>			
		</div>
		<div class="form-item" style="text-align:right;">
			<input type="hidden" name="pid" value="$pid"/>
			<span class="link" id="preview">Preview</span>
			<input class="button" type="submit" id="go" name="go" value="Submit"/>			
		</div>
	</form>
</div>
b3;
}

function show_rightbox() {
echo '<div id="right-box" style="width:24%;">';
echo	'<div class="tips-box round-corner">';
echo		'<div style="font-weight: bold;">Posting Guidelines</div>';
echo		'<ul>';
echo		'<li>Make the title short and precise</li>';
echo		'<li>You may want to link pictures and videos into your description</li>';
echo		'<li>Read and respect the <a href="tou.php">"Terms of Use"</a></li>';
echo		'</ul>';
echo	'</div>';
	show_login_menu();
echo '</div>';
}

?>