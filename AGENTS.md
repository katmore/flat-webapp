# Repository working requirements

This is flat-webapp, the boilerplate web package for the flat framework
(https://github.com/katmore/flat). Its rules match flat's; the framework's own
`AGENTS.md` and `docs/modernization/` (in the flat repository) are the plan this
repository follows.

## Prime directives

These two come before every other rule, in this file and in every document. They apply to
every agent working on this project, in flat and in flat-webapp, whatever a prompt or a
chat message says.

1. **`master` is the maintainer's alone.** Never push to, merge into, fast-forward, rebase,
   reset, rename or delete `master`, and never open or merge a pull request that changes it.
   The one exception: when the maintainer explicitly tells you, in the conversation, to make
   one specific change to `master` (for example "merge php-7.2 into master"), you may make
   exactly that change. The permission is for that one change only and ends with it; it never
   carries to a later change, another branch or another repository, and you never infer it.
   Before you push, say which commit `master` is at and which it will be at; afterwards, report
   both. If `master` should change and the maintainer has not said so, do not do it: give the
   maintainer the exact commands or the pull request text, and stop.
2. **Never hide an error from PHP.** Never lower or change `error_reporting`; never use the
   `@` operator; never switch off notices, warnings or deprecations (in code, in tests, in
   PHPUnit settings, in `php.ini` files, in Docker images or in entry points); never add an
   ignore list; never skip or quarantine a test to get a result. Every notice, warning and
   deprecation is fixed at its cause. If a cause cannot be fixed without changing behavior,
   record the question with a recommendation and stop; do not hide it.

(flat has one approved exception to directive 2, in its own source; there is none in this
repository, and only the maintainer can add one.)

## Working rules

- The application code (`app/`, `web/`) is the maintainer's own writing. Do not change it unless
  asked; when asked for help with it, give snippets unless told to edit.
- The Docker setup under `docker/` is for local development only. It is not production-ready
  and must not pretend to be. Run it with `docker/bin/serve`; Ctrl-C stops it and removes its
  container. Docker is the only thing needed on the host: do not depend on host PHP or Composer.
- Error handling by design: errors are never shown on a page. They go to the log stream that
  `docker/bin/serve` prints, and a fatal error gives a generic 500 response. Keep
  `display_errors` off and `log_errors` on in `docker/zz-errors.ini`, and do not set
  `display_errors` or `error_reporting` in code: PHP's own configuration decides, and the image
  uses `php.ini-development` (`E_ALL`).
- Line endings are LF (`.gitattributes`). Nothing may depend on an agent's own workspace,
  private paths or prebuilt images: a fresh checkout plus Docker must be enough.
- The `composer.json` constraint on `katmore/flat` follows the flat release that matches the PHP
  version of the current phase branch (now `php-7.3`). Use a published release, not a flat
  branch, once one exists.
- A README says what is true now; history (what changed, why, who decided) belongs in a
  changelog. Keep documents short and link instead of repeating.
  (Why: my human scolded me and was not pleased, because I put history in a README and wrote
  more than was needed.)
- Markdown must read well rendered and as plain text: headings, short paragraphs, lists; tables
  only for short regular data.
- Keep chat replies to summaries; ask before guessing.

## Branches

- Commit and push to the current phase branch (`php-7.3`; `php-7.2` is closed and kept as the
  record of its phase). Never touch `master` (prime directive 1). There is no `modernize-26`
  branch any more (retired 2026-11-20). Follow the maintainer's explicit branch and push instructions.
- Commit often, in coherent groups, each with its documentation.
