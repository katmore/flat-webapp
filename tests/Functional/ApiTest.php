<?php
namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Tests\Support\Http;

/**
 * The JSON side: web/api.php. The shipped application has one resource, `example`, that
 * implements GET only; everything else is answered by flat's own responses.
 */
class ApiTest extends TestCase
{
    const JSON = 'application/json';
    const GREETING = 'hello from flat-webapp';
    const FORBIDDEN = ['message' => 'forbidden resource', 'data' => null];
    const NO_INTERFACE = ['message' => 'no interface found matching the resolved request method'];

    /** @dataProvider examplePaths */
    public function testTheExampleResourceAnswersGet(string $path)
    {
        $response = Http::get($path);
        $this->assertSame(200, $response->status());
        $this->assertSame(self::JSON, $response->header('Content-Type'));
        $this->assertSame(['message' => self::GREETING, 'input' => []], $response->json());
    }

    public function examplePaths()
    {
        return [['/api.php/example'], ['/api.php/example/'], ['/webapp/api.php/example']];
    }

    public function testTheQueryStringIsTheInput()
    {
        $response = Http::get('/api.php/example?a=1&b=two&q=a%20b%26c');
        $this->assertSame(200, $response->status());
        $this->assertSame(['a' => '1', 'b' => 'two', 'q' => 'a b&c'], $response->json()['input']);
    }

    public function testAGetBodyIsNotInput()
    {
        $response = Http::request('GET', '/api.php/example', ['Content-Type: application/json'], '{"k":"v"}');
        $this->assertSame(200, $response->status());
        $this->assertSame([], $response->json()['input']);
    }

    public function testHeadSendsTheHeadersAndNoBody()
    {
        $head = Http::request('HEAD', '/api.php/example');
        $this->assertSame(200, $head->status());
        $this->assertSame(self::JSON, $head->header('Content-Type'));
        $this->assertSame('', $head->body());
    }

    /** @dataProvider forbiddenPaths */
    public function testAnythingElseIsForbidden(string $path)
    {
        $response = Http::get($path);
        $this->assertSame(403, $response->status());
        $this->assertSame(self::JSON, $response->header('Content-Type'));
        $this->assertSame(self::FORBIDDEN, $response->json());
    }

    public function forbiddenPaths()
    {
        return [
            'no path' => ['/api.php'],
            'root' => ['/api.php/'],
            'unknown resource' => ['/api.php/nothing'],
            'extra segment' => ['/api.php/example/extra'],
        ];
    }

    /**
     * A method the resource does not implement answers 500 (flat v1.72.2 on); a 405 with an
     * Allow header is planned for v2.X (flat's docs/modernization/DEFERRED-V2.md). Change these
     * expectations when that happens.
     *
     * @dataProvider unsupportedMethods
     */
    public function testAMethodTheResourceDoesNotImplementAnswers500(string $method)
    {
        $response = Http::request($method, '/api.php/example');
        $this->assertSame(500, $response->status());
        $this->assertSame(self::JSON, $response->header('Content-Type'));
        $this->assertSame(self::NO_INTERFACE, $response->json());
    }

    public function unsupportedMethods()
    {
        // PATCH is left out on purpose: flat does not know it and the application dies with a PHP
        // fatal error (Q43 in flat's docs/modernization/php-7.3/active/KNOWN-DEFECTS.md).
        return [['POST'], ['PUT'], ['DELETE'], ['OPTIONS']];
    }

    /**
     * No resource here implements POST, so the answer is the same whatever the body is.
     * Malformed JSON is not in this list on purpose: InputTrait raises a PHP fatal error for it
     * (a deliberate trigger_error, before the controller's error handlers exist), and the server-log
     * check of docker/bin/test rejects any PHP fatal error (Q44 in flat's
     * docs/modernization/php-7.3/active/KNOWN-DEFECTS.md).
     *
     * @dataProvider requestBodies
     */
    public function testARequestBodyDoesNotChangeTheAnswerWhenTheMethodIsUnsupported(string $contentType, string $body)
    {
        $response = Http::request('POST', '/api.php/example', ['Content-Type: ' . $contentType], $body);
        $this->assertSame(500, $response->status());
        $this->assertSame(self::NO_INTERFACE, $response->json());
    }

    public function requestBodies()
    {
        return [
            'json' => ['application/json', '{"k":"v"}'],
            'json without the content type' => ['text/plain', '{"k":"v"}'],
            'form' => ['application/x-www-form-urlencoded', 'a=1&b=2'],
            'json false' => ['application/json', 'false'],
            'empty' => ['application/json', ''],
        ];
    }

    /** @dataProvider overrideMethods */
    public function testAGetCanOverrideItsMethodWithAQueryParameter(string $parameter)
    {
        $response = Http::get('/api.php/example?' . $parameter . '=1&x=y');
        $this->assertSame(500, $response->status(), 'the request was handled as ' . $parameter . ', not GET');
        $this->assertSame(self::NO_INTERFACE, $response->json());
    }

    public function overrideMethods()
    {
        return [['POST'], ['PUT'], ['DELETE']];
    }

    public function testTheOverrideParameterIsCaseSensitive()
    {
        $response = Http::get('/api.php/example?post=1');
        $this->assertSame(200, $response->status());
        $this->assertSame(['post' => '1'], $response->json()['input']);
    }
}
