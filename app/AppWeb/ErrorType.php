<?php
namespace AppWeb;

/**
 * Which PHP error types end the request.
 *
 * Fatal errors (and uncaught exceptions, handled elsewhere) end the request with
 * a generic 500 response. Everything else (warnings, notices, deprecations) is
 * only logged by PHP and the request carries on, as it does in plain PHP.
 */
final class ErrorType {
   const FATAL = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR;

   /**
    * @param mixed $type an error type such as E_WARNING; anything that is not an int is not fatal
    */
   public static function is_fatal($type) : bool {
      return is_int($type) && ($type & self::FATAL) !== 0;
   }
}
