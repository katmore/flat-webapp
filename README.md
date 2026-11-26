# flat webapp
**the boilerplate flat web package**

## features
 * HTML templating
 * Front-End Routing (for the HTML template) 
 * Back-End Routing (for the RESTful API webservice) 
 
## directory map
 * `app/AppRoute/Api/Resolve/`: "Webservice Routing". Each class here is an API resource. For example `app/AppRoute/Api/Resolve/example.php` (namespace `AppRoute\Api\Resolve`, implements `\flat\core\routable`) answers `http://localhost:8080/api.php/example`. A resource that no class answers gets a 403 "forbidden resource".
 * `app/AppRoute/Api/Dependency/` (create it when needed): classes in namespace `AppRoute\Api\Dependency` run before the resource and can make a route resolution fail when a condition is not met (missing input, no database connection, missing configuration, and so on).
 * `app/Resources/design/tmpl/view/`: "HTML View Routing". `app/Resources/design/tmpl/view/home.php` answers `http://localhost:8080/view.php/home`; a view with no file answers 404.
 * `app/config/routing/`: the namespaces (`AppRoute\Api\`, `AppRoute\View\`) the two entry points route into.
 * `app/config/flat/`: configuration read through `\flat\core\config` (URL bases, design folder, page title).
 * `web/`: the web root (`api.php` and `view.php` are the entry points; `asset/` holds the application's own static files, currently one CSS file and some images; `components/` is where third-party static files would go, served through `AppWeb\ComponentsUrl`; it is empty and ignored by Git).

## Run it with Docker
Needs only Docker (with Compose). From the repository root:

```bash
docker/bin/serve
```

The script stays in the foreground and streams the server log live: every request and every PHP
error (errors are never shown on the page, only here). **Press Ctrl-C to stop the server**; it
shuts down and removes its container. Use `--detach` to run in the background instead.

It installs the PHP dependencies from `composer.lock` (flat `v1.73.2`), builds an Apache +
PHP 7.3 image, and starts it. Then open:

 * http://localhost:8080/view.php/home (HTML view)
 * http://localhost:8080/api.php/example (JSON API example)

Options (`docker/bin/serve --help`): `--port N` picks another port, `--flat ../flat` serves flat
from a local checkout instead of the Composer copy, `--install` reinstalls dependencies, and `--detach` runs it in the background (stop it with `--stop`). The Composer packages are installed into `vendor/` in your
working copy, as your own user. Apache workers use the same UID/GID so private checkout
directories remain readable without changing their permissions.

PHP settings: the entry points do not set `display_errors` or `error_reporting`; PHP's own
configuration does. The image uses PHP's `php.ini-development` (`error_reporting` is `E_ALL`), and
`docker/zz-errors.ini` turns display off and logging on. Errors are never shown on a page:
visitors get a generic message, and the details (message, file, line, stack trace) go to the
server log, which `docker/bin/serve` streams in your terminal.

**The Docker setup is for local development only. It is not production-ready and makes no attempt
to be**: it runs Apache with PHP's development settings and mounts your working copy into the
container. Deploying flat-webapp is a separate job that this repository does not do for you.

The `web/` folder is also served under `/webapp/`, because the generated asset URLs
in `app/config/flat/App/` are written for an installation at `http://example.com/webapp/`.

## Functional tests

```bash
docker/bin/test                      # all tests
docker/bin/test --filter ApiTest     # arguments go to PHPUnit
```

The tests in `tests/` send real HTTP requests to the application running behind Apache in Docker
(`docker/compose.test.yml`) and check the status, headers and body. The run also fails if the
server log shows any PHP warning, notice, deprecation, fatal error or application JSON error
record. A test-only HTTP endpoint exposes parsed request bodies. Isolated PHP tests assert native
fatal paths and the error handlers' generic response, HTTP status and logged diagnostics.
The endpoint is enabled only by the test Compose stack, which publishes no host port.

The runner reconciles dependencies with the tracked application lock on every run and checks that
the installed flat release matches it. Set `COMPOSER_PREFER_INSTALL=source` for application Git
downloads. Set `WEBAPP_GITHUB_ARCHIVES=1` to cache the locked test-tool ZIPs from GitHub's official
codeload endpoint when its API archive endpoint is unavailable. Docker is the only host requirement; this is development
tooling.

## Installation without Docker
 Composer 'create-project'...

```bash
composer create-project katmore/flat-webapp my_project_dir
```
(copies this repo and configures php dependencies)

The page is plain HTML and CSS with no JavaScript, so there is nothing else to install.

### Copyright
Flat webapp - https://github.com/katmore/flat-webapp

Copyright (c) 2012-2017 Doug Bird. All Rights Reserved.


### License
"Flat webapp" is copyrighted free software.

You can redistribute it and/or modify it under either the terms and conditions of the
"[The MIT License (MIT)](https://github.com/katmore/flat-webapp/blob/master/LICENSE)"; or the terms and conditions of the "[GPL v3 License](https://github.com/katmore/flat-webapp/blob/master/GPLv3)".
