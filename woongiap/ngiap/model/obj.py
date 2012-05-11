from google.appengine.ext import db
from ngiap import global_util

class Category(db.Model):
	pInteger = db.IntegerProperty()
	pFloat = db.FloatProperty()
	bBoolean = db.BooleanProperty()
	#__id = db.StringProperty() #??
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
	pass
	
class Faq(db.Model):
	pass
	
class Post(db.Model):
	pass
	
class User(db.Model):
	pass
