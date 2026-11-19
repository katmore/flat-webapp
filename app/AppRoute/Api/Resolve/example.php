<?php
/*
Everything in the "Resolve" directory defines an API resource endpoint
for the GET method, implement: \flat\api\method\GET
for the POST method, implement \flat\api\method\POST
etc.

depending on the HTTP method, you must define the corresponding PHP method:
   public function GET_method(\flat\input $input) {
      /*your stuff here*/
   }
*/
namespace AppRoute\Api\Resolve;


use flat\api\response;

class example extends \flat\core\controller\api implements \flat\core\routable, \flat\api\method\GET
{
   public function GET_method(\flat\input $input) {
      return new response\ok([
         "message" => "hello from flat-webapp",
         "input" => (array) $input,
      ]);
   }
}