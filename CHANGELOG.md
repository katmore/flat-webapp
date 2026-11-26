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
