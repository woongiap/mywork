<?php session_start();
require_once('mod/post.inc.php');
require_once('inc/view.inc.php');
require_once('inc/pview.inc.php');

if (!logged_in()) {
	$_SESSION['last_url'] = 'contribute.php';
	$_SESSION['last_msg'] = 'Please log in to contribute.';
	header('Location: login.php' ); // redirect to login page
	exit;
}

if (isset($_POST['go'])) {
    $title = $_POST['post_title'];
    $desc = $_POST['desc'];
    $location = $_POST['location'];
    $category_id = $_POST['category'];
    $state_code = $_POST['state'];
    $post = new Post($title, new Category($category_id), $location, $state_code, $desc);
    $post->setByUserId(logged_in());
    $pid = post_create($post);
    if ($pid > 0) {
	    header("Location: home.php?p=$pid");
	    exit;    	
    } else {
		show_edit_header('Klasz - Contribute Posting');
		show_edit_form('Contribute Posting', null, 'Sorry, error occurred.');
		show_rightbox();
		prepare_suggest();
		show_footer();	    	
    }
} else { // not a post submit, show blank form
	show_edit_header('Klasz - Contribute Posting');
	show_edit_form('Contribute Posting');
	show_rightbox();
	prepare_suggest();
	show_footer();	
}

?>
