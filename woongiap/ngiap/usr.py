UID_MAX = 2147483646

def user_spare_id():
	return mt_rand(UID_MAX/2+1, UID_MAX)

def user_new_id():
	return mt_rand(123, UID_MAX/2)

def get_confirm_code():
	return mt_rand(1000000001, PHP_INT_MAX)

def valid_login_name(s):
	q = db.GqlQuery("SELECT __id FROM User " +
                "WHERE __username = :1", s)
	return q.get() is None

def user_login(username, pw):
	q = User.all()
	q.filter("__username =", username)
	q.filter("__password =", sha(pw))
	q.filter("ustate =", 1)
	return q.get()
	
ACT_SUCCESS = 1
ACT_NO_USER = 2
ACT_INVALID_CODE = 3

def user_activate(email, code):
	return_code = ACT_SUCCESS
	u = User.all().filter("__username =", email).get()
	if u is None:
		return_code = ACT_NO_USER
	else:
		if code == u.last_key:		
			u.last_key = None
			u.ustate = 1
			u.put()
		else:
			return_code = ACT_INVALID_CODE
	return ACT_INVALID_CODE

def password_change(user_id, old_password, new_password):
	q = User.all()
	q.filter("__id =", user_id)
	q.filter("__password =", sha(old_password))
	u = q.get()
	return False if u is None
	u.__password = sha(new_password)
	u.put()
	return True

def password_reset(email, code, newpassword):
	q = User.all()
	q.filter('__username =', email)
	q.filter('last_key =', code)
	u = q.get()
	u.last_key = None
	u.__password = sha(newpassword)
	u.put()
	return True

def send_forgotpw_email(email, code):
	recipients[email] = 'Klasz User'
	email = urlencode(email)
	body = """
Hello Klasz fellow,
<br/><br/>
Please follow <a href="%s">this link</a> and regain your login to Klasz.
<br/><br/>
Your code is: %s
<br/><br/>
Thank you.
<br/>
From Klasz Team
""" % ('http://woongiap.appspot.com/fpw?e='+email+'&code='+code, code,)

	alt_body = """
Hello Klasz fellow,
\n\n
Please follow <a href="%s">this link</a> and regain your login to Klasz.
<br/><br/>
Your code is: %s
\n\n
Thank you.
\n
From Klasz Team
""" % ('http://woongiap.appspot.com/fpw?e='+email+'&code='+code, code,)

	send_mail(recipients, "Klasz Login Retrieval", body, alt_body)	
	
def password_reset_request(email):
	code = get_confirm_code()
	q = User.all()
	q.filter('last_key =', None)
	q.filter('__username =', email)
	u = q.get()
	if u is not None:
		u.last_key = code
		u.put()
		send_forgotpw_email(email, code)
		return 1
	else:
		return 0
		
def get_user(uid):
	key = db.Key.from_path('User', uid)
	q = "SELECT * FROM User where __key__ = :1", key)
	return q.get()