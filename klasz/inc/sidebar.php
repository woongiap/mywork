<?php 
require_once('mod/global.inc.php');
echo '<script type="text/javascript" src="js/k_sb.min.js"></script>';
echo <<<tips
	<div class="tips-box round-corner" style="text-align:center;">
		<a href="#" id="learn-more">Learn more</a>
		<ul id="tips" style="display:none;text-align:left;padding:8px;">
			<li>Place classifieds under relevant category</li>
			<li>Embed pictures and videos into postings whenever appropriate</li>
			<li>Read and respect the <a href="tou.php">"Terms of Use"</a></li>			
		</ul> 
		<div class="big-button round-corner" onclick="window.location.href='contribute.php';">Contribute</div>
	</div>
tips;
show_login_menu();
echo <<<rest
	<div style="margin-top:5px;border:1px solid #CCC;" class="round-corner">
		<div id="tell-link" class="link" style="margin:5px;">Tell your friends</div>
		<div id="tf" style="display:none;">
			<div style="display:none;" class="msg-bar"></div>
			<form name="f-tf" id="f-tf" method="post" style="margin:5px;">			
			<label>Your Name</label><br/>
			<input type="text" name="submitter" style="width:96%;"/>
			<input type="hidden" name="ft" value="tell"/>			
			<label>Friend's emails<span>(one per line)</span></label><br/>
			<textarea name="emails" rows="3" style="width:96%;"></textarea><br/>
			<label>Message<span>(optional)</span></label>
			<div><textarea name="msg" rows="8" style="width:96%;"></textarea></div>
			<input type="submit" class="button" id="b-tellfriend" value="Tell" />
			</form>
		</div>
	</div>
	<div style="margin-top:5px;border: 1px solid #CCC;" class="round-corner">
		<div id="feedback-link" class="link" style="margin:5px;">Send us feedback</div>
		<div id="sf" style="display:none;">
			<div style="display:none;" class="msg-bar"></div>
			<form name="f-sf" id="f-sf" method="post" style="margin:5px;">
			<label>Email<span>(optional)</span></label>
			<div><input style="width:96%;" type="text" name="email"/>
			<input type="hidden" name="ft" value="feedback"/>
			</div>
			<div>Feedback</div>
			<div><textarea style="width:96%;" name="feedback" rows="8"></textarea></div>
			<input type="submit" class="button" id="b-sendfeedback" value="Send" />
			</form>
		</div>
	</div>
	<div style="margin-top:5px;padding:5px;">
		<img src="img/feed-icon.png" style="margin-right:5px;"></img><a href="feed.php">Latest posts feed</a>
	</div>
rest;
?>