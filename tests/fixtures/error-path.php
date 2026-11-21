<?php
namespace AppWeb {
    // Isolate the parser boundary for a native fatal-error test. The HTTP body
    // tests exercise php://input itself through Apache instead.
    function file_get_contents($path) {
        return $path === 'php://input' ? '{broken' : \file_get_contents($path);
    }
}
namespace {
    require __DIR__.'/../../vendor/autoload.php';
    register_shutdown_function(function () {
        file_put_contents($_SERVER['argv'][2], json_encode(['status'=>http_response_code()]));
    });
    switch ($argv[1]) {
        case 'malformed-json':
            $input = new class {
                use \AppWeb\InputTrait;
                public function read() { return $this->_get_input('POST', 'application/json'); }
            };
            $input->read();
            break;
        case 'patch':
            new \AppWeb\ApiController('example', __DIR__.'/../../app', 'AppRoute\\Api', 'PATCH', '', new \flat\input\map);
            break;
        case 'handled-fatal':
            $handler = new class { use \AppWeb\ErrorTrait; };
            $handler->_enable_custom_errors();
            trigger_error('review fatal canary', E_USER_ERROR);
            break;
        case 'handled-exception':
            $handler = new class { use \AppWeb\JsonErrorTrait; };
            $handler->_enable_custom_errors();
            throw new \RuntimeException('review exception canary');
        default:
            throw new \InvalidArgumentException('Unknown error fixture');
    }
    echo 'unexpected success';
}
