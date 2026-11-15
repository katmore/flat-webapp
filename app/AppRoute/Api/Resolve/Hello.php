<?php
namespace AppRoute\Api\Resolve;

use flat\api\response;

/**
 * Example API resource: http://localhost:8080/api.php/Hello
 *
 * The class name is the resource name. Each HTTP method the resource answers is an
 * interface from flat\api\method plus the matching *_method() function.
 */
class Hello extends \flat\core\controller\api implements \flat\core\routable, \flat\api\method\GET
{
   public function GET_method(\flat\input $input) {
      return new response\ok([
         "message" => "hello from flat-webapp",
         "input" => (array) $input,
      ]);
   }
}
