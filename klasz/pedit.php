<?php session_start();
require_once('mod/post.inc.php');
require_once('inc/view.inc.php');
require_once('inc/pview.inc.php');

if (!logged_in()) {
	$_SESSION['last_url'] = 'contribute.php';
	$_SESSION['last_msg'] = 'Please log in to edit posting.';
	header('Location: login.php' ); // redirect to login page
	exit;
}

if (isset($_POST['go'])) {
	$pid = $_POST['pid'];
    $title = $_POST['post_title'];
    $desc = $_POST['desc'];
    $location = $_POST['location'];
    $category_id = $_POST['category'];
    $state_code = $_POST['state'];
    $post = new Post($title, new Category($category_id), $location, $state_code, $desc);
    $post->setByUserId(logged_in());
    $post->setId($pid);
    $pid = post_update($post);
    if ($pid) {
	    header("Location: home.php?p=$pid");
	    exit;    	
    } else {
		show_edit_header('Klasz - Edit Posting');
		show_edit_form('Edit Posting', null, 'Sorry, error occurred.');
		show_rightbox();
		prepare_suggest();
		show_footer();	    	
    }
} else { // not a post submit, get posting data
	if (empty($_GET['p'])) {
		header('Location: home.php' );
		exit;
	}
	$pid = $_GET['p'];
	if ($post = post_get($pid, false)) {
		if ($post->getByUserid() != logged_in()) {
			header('Location: home.php' );
			exit;			
		}
		show_edit_header('Klasz - Edit Posting');
		show_edit_form('Edit Posting', $post);
		show_rightbox();
		prepare_suggest();
		show_footer();		
	} else {
		header('Location: home.php' );
		exit;		
	}
}

?>
