from google.appengine.ext import db
from ngiap import global_util

class Category(db.Model):
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
	
	__id = db.StringProperty()
	
	def __init__(self, id):
		super(Category, self).__init__()
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
	__c_date = db.DateTimeProperty()
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
	__category = None
	__num_view = db.IntegerProperty()
	__num_comment = db.IntegerProperty()
	__num_like = db.IntegerProperty()
	__state = db.IntegerProperty()
	__create_date = db.DateTimeProperty()
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
	pass
	# TODO
