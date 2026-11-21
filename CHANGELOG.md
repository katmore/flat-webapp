# PHP 7.3 changes

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
