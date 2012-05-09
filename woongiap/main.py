# Standard Python imports.
import os
import sys
import logging

#os.environ['DJANGO_SETTINGS_MODULE'] = 'settings'

from google.appengine.ext.webapp import util
import django.core.handlers.wsgi
from views import req

def main():
  #sys.stdout.write(req.test())
  #print req.test()
  # Ensure the Django zipfile is in the path if required.
  # Create a Django application for WSGI.
  application = django.core.handlers.wsgi.WSGIHandler()
  # Run the WSGI CGI handler with that application.
  util.run_wsgi_app(application)

if __name__ == '__main__':
  main()
