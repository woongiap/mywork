from django.http import HttpResponse
from django.shortcuts import render_to_response
import datetime
from ngiap.model.obj import Category, User
from google.appengine.ext import db

def main(req):
    	now = datetime.datetime.now()
    	handmade_key = db.Key.from_path('Category', 8891123)
    	c = Category('180', key=handmade_key)
    	c.put()
    	u = User()
    	u.setBirthdate(datetime.date(1977,9,4))
    	html = "<html><body>Now %s : %s key(%s) dob:%s</body></html>" % \
    		(now, c.getName(), c.key().id(), u.getBirthdateDBStr())
    	return HttpResponse(html)
    	
def serve(req):
	return render_to_response("fluid.html", {'req_method':req.method,
						'a':test(),})
	
def test():
	return "testing"
