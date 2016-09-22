# webapp distribution
boilerplate flat web application
https://github.com/katmore/flat-webapp

## features
 * HTML templating
 * Front-End Routing (for the HTML template) 
 * Back-End Routing (for the RESTful API webservice) 
 
## directories
 * /webapp/app/AppRoute/Api routing definitions (class maps to URI http://example.com/webapp/web/api.php/)
   * http://example.com/webapp/web/api.php/MyResource will instantiate class named \AppRoute\Api\Resolve\MyResource defined in /webapp/app/AppRoute/Api/MyResource.php
 * /webapp/app/Resources/design/tmpl/view/ page definitions
   * /webapp/app/Resources/design/tmpl/view/home.php maps to URI http://example.com/webapp/web/view.php/home
 * 

### Copyright Notice
The flat framework. 
Copyright (c) 2012-2016 Doug Bird, Garrison Koch. All Rights Reserved.
https://github.com/katmore/flat
[See LICENSE.txt](LICENSE.txt) Full Copyright and licensing information. 

The flat framework is copyrighted free software.
You can redistribute it and/or modify it under either the terms and conditions of the
"The MIT License (MIT)" (see the file MIT-LICENSE.txt); or the terms and conditions
of the "GPL v3 License" (see the file GPL-LICENSE.txt).