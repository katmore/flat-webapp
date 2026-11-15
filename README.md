# flat webapp
**the boilerplate flat web package**

## features
 * HTML templating
 * Front-End Routing (for the HTML template) 
 * Back-End Routing (for the RESTful API webservice) 
 
## directory map
 * `app/AppRoute/Api/Resolve/`: "Webservice Routing". Each class here is an API resource. For example `app/AppRoute/Api/Resolve/Hello.php` (namespace `AppRoute\Api\Resolve`, implements `\flat\core\routable`) answers `http://localhost:8080/api.php/Hello`. A resource that no class answers gets a 403 "forbidden resource".
 * `app/Resources/design/tmpl/view/`: "HTML View Routing". `app/Resources/design/tmpl/view/home.php` answers `http://localhost:8080/view.php/home`; a view with no file answers 404.
 * `app/config/routing/`: the namespaces (`AppRoute\Api\`, `AppRoute\View\`) the two entry points route into.
 * `app/config/flat/`: configuration read through `\flat\core\config` (URL bases, design folder, page title).
 * `web/`: the web root (`api.php` and `view.php` are the entry points; `asset/` holds the application's own CSS, JS and images; `components/` is created by Bower).

## Run it with Docker
Needs only Docker (with Compose). From the repository root:

```bash
docker/bin/serve
```

It installs the PHP dependencies with Composer (flat comes from the `php-7.2` branch of
katmore/flat) and the front-end components with Bower, builds an Apache + PHP 7.2 image,
and starts it. Then open:

 * http://localhost:8080/view.php/home (HTML view)
 * http://localhost:8080/api.php/Hello (JSON API example)

Options (`docker/bin/serve --help`): `--port N` picks another port, `--flat ../flat` serves flat
from a local checkout instead of the Composer copy, `--install` reinstalls dependencies, and
`--stop` stops the server. The Composer and Bower packages are installed into `vendor/` and
`web/components/` in your working copy, as your own user.

The `web/` folder is also served under `/webapp/`, because the generated asset URLs
in `app/config/flat/App/` are written for an installation at `http://example.com/webapp/`.

## Installation without Docker
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
