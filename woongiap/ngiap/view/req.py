from django.http import HttpResponse
from django.shortcuts import render_to_response
import datetime

def main(req):
    	now = datetime.datetime.now()
    	html = "<html><body>Now %s.</body></html>" % now
    	return HttpResponse(html)
    	
def serve(req):
	return render_to_response("fluid.html", {'req_method':req.method,
						'a':test(),})
	
def test():
	return "testing"
