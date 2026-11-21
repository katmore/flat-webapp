# PHP 7.3 changes

## Lock application dependencies (finding 8)

Require flat 1.73.* and track the application lock, currently v1.73.0 at 8bee551. The test runner
reconciles existing installs and verifies the installed release against the lock. Apache workers
use the configured development UID/GID. Source downloads are selectable when archives are
unavailable; test images omit downloaded Git histories. PHP remains 7.3.33.
