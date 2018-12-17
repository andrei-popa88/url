import sphinx_rtd_theme
project = 'KepplerPl_url'
copyright = '2018, Andrei Popa'
author = 'Andrei Popa'
version = ''
release = 'v2.0.0'


extensions = [
]

templates_path = ['_templates']

source_suffix = '.rst'

master_doc = 'index'

language = None

exclude_patterns = ['_build', 'Thumbs.db', '.DS_Store']

pygments_style = None


html_theme = 'sphinx_rtd_theme'
html_static_path = ['_static']

htmlhelp_basename = 'KepplerPl_urldoc'



latex_documents = [
    (master_doc, 'KepplerPl_url.tex', 'KepplerPl_url Documentation',
     'Andrei Popa', 'manual'),
]


man_pages = [
    (master_doc, 'kepplerpl_url', 'KepplerPl_url Documentation',
     [author], 1)
]


texinfo_documents = [
    (master_doc, 'KepplerPl_url', 'KepplerPl_url Documentation',
     author, 'KepplerPl_url', 'One line description of project.',
     'Miscellaneous'),
]


epub_title = project

# The unique identifier of the text. This can be a ISBN number
epub_exclude_files = ['search.html']

import sphinx_rtd_theme
html_theme = "sphinx_rtd_theme"
html_theme_path = [sphinx_rtd_theme.get_html_theme_path()]

# Set up PHP syntax highlights
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
lexers["php"] = PhpLexer(startinline=True, linenos=1)
lexers["php-annotations"] = PhpLexer(startinline=True, linenos=1)
primary_domain = "php"
