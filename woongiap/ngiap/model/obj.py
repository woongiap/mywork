from datetime import date
from google.appengine.ext import db
from ngiap import global_util

class Category(db.Model):
	"""
	pInteger = db.IntegerProperty()
	pFloat = db.FloatProperty()
	bBoolean = db.BooleanProperty()
	bText = db.TextProperty()
	bByteStringProperty = db.ByteStringProperty()
	bBlobProperty = db.BlobProperty()
	bDateProperty = db.DateProperty ()
	bTimeProperty = db.TimeProperty()
	bDateTimeProperty = db.DateTimeProperty()
	bGeoPtProperty = db.GeoPtProperty()
	bPostalAddressProperty = db.PostalAddressProperty()
	bPhoneNumberProperty = db.PhoneNumberProperty()
	bEmailProperty = db.EmailProperty()
	bUserProperty = db.UserProperty()
	bIMProperty = db.IMProperty()
	bLinkProperty = db.LinkProperty()
	bCategoryProperty = db.CategoryProperty()
	bRatingProperty = db.RatingProperty()
	"""
	
	__id = db.StringProperty()
	
	def __init__(self, id, **kwds):
		super(Category, self).__init__(**kwds)
		self.__id = id
	
	def getId(self):
		return self.__id
		
	def getName(self):
		return global_util.k_category_getname(self.__id)
		
	def getTableName(self):
		return global_util.k_category_gettabname(self.__id)
		
class Comment(db.Model):
	__c_id = db.IntegerProperty()
	__c_text = db.StringProperty(multiline=True)
	__c_date = db.DateTimeProperty(auto_now_add=True)
	__c_byname = db.StringProperty()
	__c_byuserid = db.IntegerProperty()
	
	def setId(self, id):
		self.__c_id = id
		
	def getId(self):
		return self.__c_id
		
	def setText(self, s):
		self.__c_text = s
		
	def getText(self):
		return self.__c_text
		
	def setDate(self, d):
		self.__c_date = d
		
	def getDate(self):
		return self.__c_date
		
	def setCommentUsername(self, s):
		self.__c_byname = s
	
	def getCommentUsername(self):
		return self.__c_byname

	def setByUserId(self, id):
		self.__c_byuserid = id

	def getByUserId(self):
		return self.__c_byuserid

class Faq(db.Model):
	q = db.StringProperty(multiline=True)
	a = db.StringProperty(multiline=True)
	dorder = db.IntegerProperty()
	
	def __init__(self, q, a, dorder=1):
		super(Faq, self).__init__()
		self.q = q
		self.a = a
		self.dorder = dorder
	
class Post(db.Model):
	__id = db.IntegerProperty()
	__title = db.StringProperty
	__html_desc = db.StringProperty(multiline=True)
	__location = db.StringProperty()
	__divcode = db.StringProperty()
	__category = db.ReferenceProperty(Category)
	__num_view = db.IntegerProperty()
	__num_comment = db.IntegerProperty()
	__num_like = db.IntegerProperty()
	__state = db.IntegerProperty()
	__create_date = db.DateTimeProperty(auto_now_add=True)
	__by_username = db.StringProperty()
	__by_userid = db.IntegerProperty()
	
	def __init__(self, title, category, location, divcode, desc=""):
		super(Post, self).__init__()
		self.__title = title
		self.__category = category
		self.__location = location
		self.__divcode = divcode
		self.__html_desc = desc
	
	def getTitle(self):
		return self.__title		
	def getCategory(self):
		return self.__category
	def getLocation(self):
		return self.__location
	def getDivcode(self):
		return self.__divcode	
	def setDesc(self, desc):
		self.__html_desc = desc
	def getDesc(self):
		return self.__html_desc
	
	def setId(self, id):
		self.__id = id
	def getId(self):
		return self.__id
	
	def setState(self, state):
		self.__state = state
	def getState(self):
		return self.__state
	
	def setNumView(self, num):
		self.__num_view = num
	def getNumView(self):
		return self.__num_view
		
	def setNumComment(self, num):
		self.__num_comment = num
	def getNumComment(self):
		return self.__num_comment
	
	def setNumLike(self, num):
		self.__num_like = num
	def getNumLike(self):
		return self.__num_like
	
	def setCreateDate(self, date):
		self.__create_date = date
	def getCreateDate(self):
		return self.__create_date
	
	def setByUsername(self, name):
		self.__by_username = name
	def getByUsername(self):
		return self.__by_username

	def setByUserId(self, n):
		self.__by_userid = n
	def getByUserId(self):
		return self.__by_userid

class User(db.Model):
	__username = db.StringProperty()
	__alt_email = db.EmailProperty()
	__password = db.StringProperty()
	__display_name = db.StringProperty()
	__gender = db.StringProperty()
	__birth_date = db.DateProperty()
	__id = db.IntegerProperty()

	def __init__(self, password=''):
		super(User, self).__init__()
		self.__password = password

	def setId(self, id):
		self.__id = id;
	def getId(self):
		return __self.id

	def setUsername(self, s):
		self.__username = s
	def getUsername(self):
		return self__.username

	def setDisplayname(self, s):
		self.__display_name = s
	def getDisplayname(self):
		return self.__display_name

	def setAltEmail(self, s):
		self.__alt_email = s
	def getAltEmail(self):
		return self.__alt_email

	def setGender(self, g):
		self.__gender = g
	def getGender(self):
		return self.__gender

	def setBirthdate(self, bdate):
		self.__birth_date = bdate
	def getBirthdate(self):
		return self.__birth_date
	def getBirthdateDBStr(self):
		if self.__birth_date is None:
			return "NULL"
		return self.getBirthdate().strftime("%Y-%b-%d")

	def signup(self):
		self.__password = sha(self.__password)
		code = get_confirm_code()
		self.__id = user_new_id()
		# persist the model
		self.put()
		"""
		q = insert into k_user
			(user_id, user_name, user_passwd, display_name, alt_email, signup_date, gender, birth_date, last_key)
				values (new_id, 'username', sha('password'), 'displayname', 'alt_email', now(),
				'gender',bdate_str,'code')
		"""
		confirm_code_send(self.getUsername(), code)
		return True

	def getDBAccount(self):		
		#k = Key.from_path('User', self.getId())
		#db.get(k)
		self = db.Model.get_by_id(self.getId())
		#select * from k_user where user_id=id
		return self.__username is not None

	def updateDBAccount(self):
		self.put()
		"""
		update k_user set display_name=getDisplayname()
		,alt_email=getAltEmail()
		where user_id=getId()
		"""
		return True
		