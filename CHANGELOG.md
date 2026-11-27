# PHP 7.4 changes

## Lock phpstan/phpdoc-parser 2.4.2

The application lock moves phpstan/phpdoc-parser (used by jms/serializer) from 1.33.0 to 2.4.2, the newest release PHP 7.4.33 allows. Only that package changes. 67 tests and 203 assertions pass, and the server log has no PHP message.

## Lock jms/serializer 3.32.9

The application lock moves jms/serializer from 3.29.1 to 3.32.9, the newest release PHP 7.4.33 allows. Only that package changes. 67 tests and 203 assertions pass, and the server log has no PHP message.

## Lock doctrine/deprecations 1.2.0

The application lock moves doctrine/deprecations from 1.1.6 to 1.2.0, the newest release PHP 7.4.33 allows. Only that package changes. 67 tests and 203 assertions pass, and the server log has no PHP message.

## Lock flat v1.74.0

The application lock moves from flat v1.73.2 to the published release v1.74.0 (2812136); the constraint is now
1.74.* and the `php` requirement `>=7.4.0`, as flat's. Only flat changes in the lock (the content hash aside).
`DependencyLockTest` expects a 1.74.x framework. 67 tests and 203 assertions pass with PHP 7.4.33, and the server
log has no PHP message.

## Tooling: the Composer image rebuild of 2026-11-27

The `composer:2.11` pin moves to the rebuild of 2026-11-27 (`sha256:5b2137f1...`): still Composer 2.11.3, on updated
base layers. It is used by `docker/compose.yml` (`docker/bin/serve`) and the test image build. The application code
and locks are unchanged. 67 tests and 203 assertions pass.

## PHP 7.4.33 test and development images

The web image is `php:7.4-apache` and the test image `php:7.4-cli`, both pinned by digest to the final 7.4.33
builds (`sha256:c9d7e608...` and `sha256:620a6b9f...`). The Composer platform pin is 7.4.33 in `composer.json` and
`tests/docker/composer.json`; both locks are refreshed (content hash and platform only, no package moved).
`README.md`, `AGENTS.md` and the script comments name PHP 7.4. The application code is unchanged. 67 tests and
203 assertions pass, and the server log has no PHP message. The application still locks flat v1.73.2; it moves
to the published 7.4 release once there is one.

# PHP 7.3 changes

## Lock flat v1.73.2

The application lock moves from flat v1.73.1 to the published release v1.73.2 (673df9f); the constraint stays
1.73.*. Only flat changes in the lock. flat v1.73.2 brings updated Markdown and JavaScript minifier libraries and a
Markdown converter that escapes raw HTML by default. 67 tests and 203 assertions pass with PHP 7.3.33.

## Tooling: Composer 2.11.3

The `composer:2.11` image (used by `docker/bin/serve` and the test image build) is pinned to the build of
2026-11-25, Composer 2.11.3 (was 2.11.2). The `php:7.3-apache` and `php:7.3-cli` pins are already the final
7.3 builds. 67 tests and 203 assertions pass.

## Lock symfony/yaml 5.4.57

The application lock moves symfony/yaml from v5.4.56 to v5.4.57, a patch release of 2026-11-25 and the only
package with a newer release that PHP 7.3.33 allows. Only that package changes. 67 tests and 203 assertions pass.

## Lock flat v1.73.1

The application lock moves from flat v1.73.0 to the published release v1.73.1 (3518235); the
constraint stays 1.73.*. Only flat changes in the lock. 67 tests and 203 assertions pass with PHP
7.3.33.

## Lock application dependencies (finding 8)

Require flat 1.73.* and track the application lock, currently v1.73.0 at 8bee551. The test runner
reconciles existing installs and verifies the installed release against the lock. Apache workers
use the configured development UID/GID. Source downloads are selectable when archives are
unavailable; test images omit downloaded Git histories. PHP remains 7.3.33.

## Check native and structured error logs (finding 5)

Reject PHP warnings, notices, deprecations, catchable/recoverable fatals and application JSON
error records, including truncated records. Tests verify the log command's exit status and run
real error handlers in isolated PHP processes, asserting their response body, status and logs.

## Observe parsed HTTP input (finding 6)

Add a test-only HTTP endpoint using the real InputTrait and php://input. Nine cases check JSON,
forms and GET input directly. The endpoint is enabled only in the test stack, with no host port.
A scratch parser returning empty input makes six new checks fail while the five existing
unsupported-method body checks still pass. No application or web entry-point code changed.

Validation: 66 tests and 196 assertions pass with PHP 7.3.33 and the strict server-log gate.

## Keep test images small with source-download fallbacks

Remove Git histories before copying test tools into the runtime image. The optional
WEBAPP_GITHUB_ARCHIVES=1 mode seeds Composer's ZIP cache from official GitHub archives for the
exact locked commits; it does not alter the lock or package versions. Application and test-tool
transport preferences are separate. The verified runtime image is about 470 MB; all 66 tests
and the strict log check pass after rebuilding it.

## Tooling: Composer 2.11 (was 2.2)

The Composer image used to install the application and the test dependencies is `composer:2.11`,
pinned by digest. Installs from the existing locks are unchanged; the 66 tests and the log check pass.

## Tooling: PHPUnit 9.6 (was 8.5)

The test lock moves to PHPUnit 9.6.39 on the PHP 7.3.33 platform (9.6 is the last line for PHP 7.3).
Two `assertRegExp` calls became `assertMatchesRegularExpression`. Same 66 tests and assertions.

## Fix: the server-log check failed when the host user is not the container user

`docker/bin/test` made its log copy with `mktemp` (mode 0600), which the test container, running as
user 1000, could not read when the host user was root (typical in CI). The check then printed "Usage"
and failed a green run. The copy is now readable, and `check-server-log.php` says "Cannot read the log
file" for an unreadable file and keeps "Usage" for a wrong argument count. A new test covers both messages.

## Dependencies: Guzzle 7.17.0 and psr7 2.13.6 in the application lock

The tracked `composer.lock` takes the two patch updates PHP 7.3.33 allows (Guzzle 7.16.5 to 7.17.0, psr7 2.13.5
to 2.13.6); the pinned flat release is unchanged. The 67 tests and the log check pass.
