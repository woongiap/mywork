import webapp2
import jinja2
import os

jinja_environment = jinja2.Environment(
    loader=jinja2.FileSystemLoader(os.path.dirname(__file__)))

class Fluid(webapp2.RequestHandler):
  def get(self):
    template = jinja_environment.get_template('pages/fluid.html')
    self.response.out.write(template.render())
