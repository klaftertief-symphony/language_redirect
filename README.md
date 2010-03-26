# Language Redirect #

Adds language redirection to Symphony.

- Version: 1.0beta
- Date: 2010-03-26
- Requirements: Symphony 2.0.x
- Author: Jonas Coch, jonas@klaftertief.de
- GitHub Repository: <http://github.com/klaftertief/language-redirect>

## Synopsis ##

Language Redirect provides an event to redirect visitors based on browser settings, values in a cookie or default settings. It adds language and region parameters to the parameter pool and renders those parameters in a clean way at the beginning of the URL.

**This is a beta extension.**

## Installation & Updating ##

**This extensions modifies the `.htaccess` file. You schould always make a backup before you install the extension or update the preferences.**

Information about [installing and updating extensions](http://symphony-cms.com/learn/tasks/view/install-an-extension/) can be found in the Symphony documentation at <http://symphony-cms.com/learn/>.

## Usage ##

Language Redirect adds a new settings field to the Preferences Page. There you can add your supported language codes as a comma separated list. A language code has to be either a two character string like `en` (language) or a five character string like `en-au` (language-region). The extensions adds the language part as `$url-language` and the optional region part as `$url-region` (normal get parameters) to the parameter pool for usage in your datasources or XSL templates.

The event stores the current language an region parameters in a cookie. Visitors will be redirected depending on settings in the following order.

1. saved parameters in the cookie
2. first matched language code in browser settings
3. default language code (first in saved preferences)