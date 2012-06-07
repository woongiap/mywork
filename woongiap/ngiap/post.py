# possible value in k_post.pstate
PS_PUBLISH = 1
PS_DRAFT = 2 # draft = unpublish
PS_REMOVE = 3

PG_ROW = 0
PG_ROW_VIEW = 28

# not used currently
class State {
	def __init__(self, div_code, name):
		self.__div_code = div_code
		self.__name = name
	
	def getDivcode(self):
		return self.__div_code
		
	def getName():
		return self.__name

def post_increase_counter(uid, cid):
	key = db.Key.from_path('User', uid)
	q = "SELECT * FROM User where __key__ = :1", key)
	user = q.get()
	user.num_post++
	user.put()
	key = db.Key.from_path('Category', cid)
	q = "SELECT * FROM Category where __key__ = :1", key)
	category = q.get()
	category.num_post++

def k_strip_tags(s):
	# TODO: regex replace in python
	return preg_replace('/<[^<>]*>/', ' ', s)

words = {'.':' ',
		',':' ',
		';':' ',
		'bkt':'bukit',
		'tmn':'taman',
		'jln':'jalan',
		' bt ':' batu ',
		' kl ':' kuala lumpur ',	
		' teh ':' tehteh ',
		' har ':' harhar ',
		' pan ':' panpan ',
		' yee ':' yeeyee ',
		'dms':'document management system',
		' mee ':' noodle '}

def expand_words(phrase):
	phrase = ' ' + phrase + ' '
	# TODO: replace in python
	return str_ireplace(array_keys($words), array_values($words), $phrase)

def prepare_fulltext_desc(desc):
	# <b>hello&nbsp;,bro&amp;this&nbsp;&lt;cool&gt;</b> becomes hello&nbsp;,bro&ampthis&nbsp;&lt;cool&gt;
	desc = k_strip_tags(desc)
	# hello&nbsp;,bro&amp; becomes hello ,bro&this <cool>
	desc = html_entity_decode($desc, ENT_QUOTES, 'utf-8')
	# TODO: check if <cool> can be searched, expand 3-char word
	return trim(expand_words($desc))

def unindex_post(post_id):
	any_table_name = k_category_gettabname(ANY_CATEGORY)
	$q = "delete from $any_table_name where post_id=$post_id";
	$mysqli->query($q);
	$q = "select category_id from k_post where post_id=$post_id";
	if ($result = $mysqli->query($q)) {
		$row = $result->fetch_assoc();
		$table_name = k_category_gettabname($row['category_id']);
		if ($table_name != $any_table_name) {
			$q = "delete from $table_name where post_id=$post_id";
			$mysqli->query($q);

def index_post(Post $post, $mysqli):
	$title = expand_words($post->getTitle());
	$loc = expand_words($post->getLocation());
	$s_name = k_state_getname($post->getDivcode());
	$c_table = $post->getCategory()->getTableName();
	$c = $post->getCategory()->getId();
	$ft_desc = prepare_fulltext_desc($post->getDesc());
	$new_id = $post->getId();	
	$q = "insert into $c_table (post_id, title, description, location) 
			values ($new_id,'$title','$ft_desc',concat('$loc ','$s_name') )";
	$mysqli->query($q);
	if ($c != ANY_CATEGORY) {
		$q = "insert into k_ft_999 (post_id, title, description, location) 
				values ($new_id,'$title','$ft_desc',concat('$loc ','$s_name') )";
		$mysqli->query($q);		

def post_new_id():
	return mt_rand(123, PHP_INT_MAX/2-1);

def spare_post_id():
	return mt_rand(PHP_INT_MAX/2, PHP_INT_MAX-123);

def post_create(Post $post):
	$title = $post->getTitle();
	$desc = $post->getDesc();
	$loc = $post->getLocation();
	$c = $post->getCategory()->getId();
	$s = $post->getDivcode();
	$uid = $post->getByUserId();
	// should put every dml below into single transation
	$new_id = post_new_id();
	$post->setId($new_id);
	$q = "insert into k_post (post_id, title, html_desc, location, category_id, div_code, create_user_id, create_date) 
			values ($new_id, '$title','$desc','$loc',$c,'$s', $uid,	now() )";
	$mysqli = k_get_mysqli();
	$mysqli->query($q);
	$post->setId($new_id);
	if ($mysqli->errno == ER_DUP_ENTRY) {
		$new_id = spare_post_id();  // try again
		$q = "insert into k_post (post_id, title, html_desc, location, category_id, div_code, create_user_id, create_date) 
				values ($new_id, '$title','$desc','$loc',$c,'$s', $uid,	now() )";
		$mysqli->query($q);
		$post->setId($new_id);
	}
	$affected_rows = $mysqli->affected_rows;
	index_post($post, $mysqli);	
	post_increase_counter($mysqli, $uid, $c);	
	$mysqli->close();
	if ($affected_rows == 1) return $new_id;
	return false;

def unpublish_post($post_id):
	$q = "update k_post set pstate=".PS_DRAFT." where post_id=$post_id";
	$mysqli = k_get_mysqli();
	$mysqli->query($q);
	$num_row = $mysqli->affected_rows;
	unindex_post($post_id, $mysqli);
	$mysqli->close();
	return $num_row == 1;

def post_update(Post $post):
	$title = $post->getTitle();
	$desc = $post->getDesc();
	$loc = $post->getLocation();
	$c = $post->getCategory()->getId();
	$s = $post->getDivcode();
	$uid = $post->getByUserId();
	$pid = $post->getId();
	$mysqli = k_get_mysqli();
	# TODO: check if data changed
	unindex_post($pid, $mysqli);
	$q = "update k_post set title = '$title', html_desc = '$desc', location = '$loc',
		 category_id = $c, div_code = '$s' where post_id=$pid and create_user_id=$uid";	
	$mysqli->query($q);
	$affected_rows = $mysqli->affected_rows;
	index_post($post, $mysqli);
	post_add_history($mysqli, $pid, UP_EDIT);
	$mysqli->close();
	return $pid;	

def populate_post(&$row, $full=0):
	$post = new Post($row['title'], new Category($row['category_id']), 
					$row['location'], $row['div_code']);
    $post->setId($row['post_id']);					
	$post->setNumView($row['num_view']);
	$post->setNumComment($row['num_comment']);
	$post->setNumLike($row['num_like']);
	$post->setCreateDate($row['create_date']);
	if ($full) {
		$post->setDesc($row['html_desc']);		
		$post->setByUsername($row['uname']);
		$post->setByUserId($row['uid']);		
	}
	return $post;	

def post_get_popular():
	$mysqli = k_get_mysqli();
	$q = 'select p.*, u.user_id uid, u.display_name uname
			from k_post p, k_user u			
			where p.create_user_id = u.user_id
			order by num_view desc, num_comment desc limit 12';
	$posts = array();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);	
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_get_recommended():
	$mysqli = k_get_mysqli();
	$q = 'select p.*, u.user_id uid, u.user_name uname
			from k_post p, k_user u			
			where p.create_user_id = u.user_id
			order by num_like desc, num_comment desc limit 12';
	$posts = array();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_get_latest():
	$mysqli = k_get_mysqli();
	$q = 'select p.*, u.user_id uid, u.user_name uname
			from k_post p, k_user u			
			where p.create_user_id = u.user_id
			order by create_date desc, num_comment desc limit 12';
	$posts = array();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def get_offset($pg):
	if ($pg == 0) return 0;
	return (PG_ROW * $pg) - (PG_ROW - PG_ROW_VIEW);

def search($query, $c, $s, $pg=0):
	$term = expand_words($query);
	$category = new Category($c);
	$div = $s==ANY_STATE?'%':$s;
	$offset = get_offset($pg);
	$q = "select p.*
			from k_post p, ".$category->getTableName()." ftp
			where (match(ftp.title) against ('".$term."')
			or match(ftp.description) against ('".$term."')
			or match(ftp.location) against ('".$term."')) 
			and ftp.post_id=p.post_id			
			and p.div_code like '".$div."'
			order by p.num_like desc, num_view desc, num_comment desc 
			limit $offset,".PG_ROW;
	$posts = array();
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_by_category($c, $pg=0):
	return search_browse($c, ANY_STATE, $pg);

def post_by_state($s, $pg=0):
	return search_browse(ANY_CATEGORY, $s, $pg);

def search_browse($c, $s, $pg=0):
	# TODO: use param bind way
	$c_clause = '';
	$s_clause = ''; 
	if (!empty($c) && ANY_CATEGORY!=$c) {
		$c_clause = "and p.category_id=$c";
	}
	if (!empty($s) && ANY_STATE!=$s) {
		$s_clause = "and p.div_code='$s'";
	}
	$offset = get_offset($pg); 
	$q = "select p.* from k_post p where 1=1 $c_clause $s_clause 
			order by num_view desc, num_comment desc, num_like desc 
			limit $offset,".PG_ROW;
	$mysqli = k_get_mysqli();			
	$posts = array();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_view($mysqli, Post $post):
	$pid = $post->getId();
	$author_id = $post->getByUserId();
	$uid = isset($_SESSION['userid'])?$_SESSION['userid']:0;
	if ($author_id != $uid) {
		$ip = $ip = $_SERVER['REMOTE_ADDR'];
		$q = "select upid from k_post_history where uptype=".UP_VIEW." and remote_ip='$ip' and uid=$uid and pid=$pid and timestampdiff(MINUTE,do_date,now())<100";
		if ($result = $mysqli->query($q)) {
			if ($result->num_rows) {
				return false;	
			}						
		}
		post_add_history($mysqli, $pid, UP_VIEW);
		$q = "update k_post set num_view=num_view+1 where post_id=$pid";				
		return $mysqli->query($q);		

def post_get($pid, $view_inc_counter=0):
	$q = "select p.*, u.display_name uname, u.user_id uid 
			from k_post p, k_user u
			where p.create_user_id = u.user_id 
			and post_id=".$pid;
	$mysqli = k_get_mysqli();
	$post = false;
	if ($result = $mysqli->query($q)) {
		if($row = $result->fetch_array()) {
			$post = populate_post($row, 1);		 
			@$result->close();
			if ($view_inc_counter) {
				post_view($mysqli, $post);
			}			
		}		
	}
	$mysqli->close();
	return $post;	

def post_by_user($u):
	$q = "select p.* from k_post p, k_user u 
			where u.user_id=$u 
			and p.create_user_id = u.user_id 
			order by num_view desc, num_comment desc, num_like desc";
	$posts = array();
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def bookmark_by_user($u):
	$q = "select p.* from k_post p, k_userpost up
			where p.post_id=up.post_id
			and up.user_id=$u and up.uptype=".UP_BOOKMARK;
	$posts = array();
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def like_by_user($u):
	$q = "select p.* from k_post p, k_userpost up
			where p.post_id=up.post_id
			and up.user_id=$u and up.uptype=".UP_LIKE;
	$posts = array();
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_feed():
	$mysqli = k_get_mysqli();
	$q = 'select p.*, u.user_id uid, u.user_name uname
			from k_post p, k_user u			
			where p.create_user_id = u.user_id
			order by create_date desc limit 18';
	$posts = array();
	if ($result = $mysqli->query($q)) {
		while ($row = $result->fetch_array()) {
			$posts[] = populate_post($row, 1);
		}		
	}
	@$result->close();
	$mysqli->close();
	return $posts;	

def post_get_title($pid):
	$mysqli = k_get_mysqli();
	$q = "select title from k_post where post_id=$pid";
	$title;
	if ($result = $mysqli->query($q)) {
		$row = $result->fetch_assoc();
		$title = $row['title'];
	} 
	$mysqli->close();
	return $title;
