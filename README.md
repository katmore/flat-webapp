# flat webapp
**the boilerplate flat web package**

## features
 * HTML templating
 * Front-End Routing (for the HTML template) 
 * Back-End Routing (for the RESTful API webservice) 
 
## directory map
 * [/webapp/app/AppRoute/Api](https://github.com/katmore/flat-webapp/tree/master/app/AppRoute) "Webservice Routing": starting point of the **route map** used by the "API" *entry point controller*, i.e., from the URL http://example.com/webapp/web/api.php/*
   * for example... 
      define a class `MyResource` defined in /webapp/app/AppRoute/Api/MyResource.php (using namespace `\AppRoute\Api\Resolve` in class definition)
   * then...
    visit the URL `http://example.com/webapp/web/api.php/MyResource`, and the `api.php` *entry point controller* will instantiate the class named \AppRoute\Api\Resolve\MyResource (from above example)
 * [/webapp/app/Resources/design/tmpl/view/](https://github.com/katmore/flat-webapp/tree/master/app/Resources/design/tmpl): "HTML View Routing": Starting point of the **route map** used by the "View" *entry-point controller*
   * [/webapp/app/Resources/design/tmpl/view/home.php](https://github.com/katmore/flat-webapp/blob/master/app/Resources/design/tmpl/view/home.php) maps to URI http://example.com/webapp/web/view.php/home

## Installation
The easiest way to get going is with a two-step process using *Composer* and *Bower*

 Step 1. Composer 'create-project'...

```bash
composer create-project katmore/flat-webapp my_project_dir
```
(copies this repo and configures php dependencies)

 Step 2. Bower update...
```bash
cd my_project_dir
bower update
```
(installs static dependencies)

### Copyright
Flat webapp - https://github.com/katmore/flat-webapp

Copyright (c) 2012-2017 Doug Bird. All Rights Reserved.


### License
"Flat webapp" is copyrighted free software.

You can redistribute it and/or modify it under either the terms and conditions of the
"[The MIT License (MIT)](https://github.com/katmore/flat-webapp/blob/master/LICENSE)"; or the terms and conditions of the "[GPL v3 License](https://github.com/katmore/flat-webapp/blob/master/GPLv3)".
