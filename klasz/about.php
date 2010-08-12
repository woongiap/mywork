<?php session_start(); 
require_once('inc/view.inc.php');
show_header('About Klasz');
echo <<<about
<style type="text/css">
.big {font-size:3em;font-weight:bold;margin-left:28px;}
.box {margin:20px 12px;width:30%;float:left;filter:alpha(opacity=60);opacity:0.6;}
</style>
<div style="width:100%;background-image:url('img/my-map.png');background-position:center center;background-repeat:no-repeat;">
<div class="box round-corner" style="background-color:#CCFF99;">
<div class="big" style="color:red;">Contribute</div>
<ul>
<li>Signup and start posting free postings</li>
<li>Place your postings into relevant category</li>
<li>Promote your products or services</li>
<li>Post job openings for free</li>
<li>Recommend the food you like to your friends</li>
<li>Tell people that you have houses, apartments, factories, shoplots or land for sale or rent</li>
</ul>
</div>
<div class="box round-corner" style="background-color:#FFCCFF;">
<div class="big" style="color:green;">Search</div>
<ul>
<li>Search comes in handy, always on top</li>
<li>Press "/" key on the keyboard to go to the search box</li>
<li>Search for postings' title, full description and location</li>
<li>Choose the desired category and state make result more relevant to you</li>
<li>Leave the search query blank, pick the category or state you are looking for, and search</li>
</ul>
</div>
<div class="box round-corner" style="background-color:#66FFFF;">
<div class="big" style="color:blue;">Collaborate</div>
<ul>
<li>Click on "Like" if you like the posting</li>
<li>Comment on postings, so that people would take that into consideration</li>
<li>Bookmark them, easy retrieve later</li>
<li>Share postings with your friends on Facebook or by email</li>
<li>Report postings for fraud, spam, obsolete, miscategorized and etc. so that we can take action</li>
<li>Get to know the most popular, most recommended postings on home page</li>
<li>Subscribe to <a href="http://www.klasz.com/feed.php">our feeds</a> for latest postings</li>
</ul>
</div>
<div class="clear"></div>
</div>
about;
show_footer();
?>
