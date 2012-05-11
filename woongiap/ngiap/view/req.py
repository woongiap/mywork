from django.http import HttpResponse
from django.shortcuts import render_to_response
import datetime
from ngiap.model.obj import Category

def main(req):
    	now = datetime.datetime.now()
    	c = Category('90')
    	c.put()
    	html = "<html><body>Now %s : %s key(%s)</body></html>" % (now, c.getName(), c.key().id())
    	return HttpResponse(html)
    	
def serve(req):
	return render_to_response("fluid.html", {'req_method':req.method,
						'a':test(),})
	
def test():
	return "testing"
