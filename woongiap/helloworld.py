import cgi
import datetime
import urllib
import webapp2

from google.appengine.ext import db
from google.appengine.api import users

import jinja2
import os

jinja_environment = jinja2.Environment(
    loader=jinja2.FileSystemLoader(os.path.dirname(__file__)))

class Entry(db.Model):
  """Models an individual diary entry"""
  author = db.UserProperty()
  content = db.StringProperty(multiline=True)
  date = db.DateTimeProperty(auto_now_add=True)

def diary_key(diary_name=None):
  """Constructs a Datastore key for a Diary entity with diary_name."""
  return db.Key.from_path('Diary', diary_name or 'default_diary')

class MainPage(webapp2.RequestHandler):
  def get(self):
    diary_name=self.request.get('diary_name')
    entries_query = Entry.all().ancestor(diary_key(diary_name)).order('-date')
    entries = entries_query.fetch(30)
    
    user = users.get_current_user()
    if user:
      if user.nickname() != 'woongiap':
        template = jinja_environment.get_template('pages/denied.html')
    	self.response.out.write(template.render())
    	return 
      url = users.create_logout_url(self.request.uri)
      url_linktext = 'Logout'
    else:
      url = users.create_login_url(self.request.uri)
      url_linktext = 'Login'

    template_values = {
      'entries': entries,
      'url': url,
      'url_linktext': url_linktext,
    }

    template = jinja_environment.get_template('pages/index.html')
    self.response.out.write(template.render(template_values))

class Diary(webapp2.RequestHandler):
  def post(self):
    # We set the same parent key on the 'Greeting' to ensure each greeting is in
    # the same entity group. Queries across the single entity group will be
    # consistent. However, the write rate to a single entity group should
    # be limited to ~1/second.
    diary_name = self.request.get('diary_name')
    entry = Entry(parent=diary_key(diary_name))

    if users.get_current_user():
      entry.author = users.get_current_user()

    entry.content = self.request.get('content')
    entry.put()
    self.redirect('/?' + urllib.urlencode({'diary_name': diary_name}))

app = webapp2.WSGIApplication([('/', MainPage),
                               ('/write', Diary)],
                              debug=True)