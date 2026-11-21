<?php
namespace Tests\Functional;
use PHPUnit\Framework\TestCase;
use Tests\Support\Http;
class InputTest extends TestCase
{
    /** @dataProvider bodies */
    public function testTheRealRequestBodyIsParsed($method, $contentType, $body, $expected)
    {
        $response = Http::request($method, '/__test/input?ignored=query', ['Content-Type: '.$contentType], $body);
        $this->assertSame(200, $response->status());
        $this->assertSame('application/json', $response->header('Content-Type'));
        $this->assertSame($expected, $response->json());
    }
    public function bodies()
    {
        return [
            ['POST','application/json','{"k":"v","zero":0,"flag":false}', ['k'=>'v','zero'=>0,'flag'=>false]],
            ['PUT','application/json; charset=utf-8','{"nested":{"k":"v"}}', ['nested'=>['k'=>'v']]],
            ['DELETE','text/plain','{"inferred":"json"}', ['inferred'=>'json']],
            ['POST','application/x-www-form-urlencoded','a=1&b=a%2Bb%26c&items%5B%5D=x&items%5B%5D=y', ['a'=>'1','b'=>'a+b&c','items'=>['x','y']]],
            ['POST','application/json','false', []],
            ['POST','application/json','null', []],
            ['POST','application/json','', []],
            ['POST','application/json','5', ['data'=>5]],
        ];
    }
    public function testGetUsesQueryFieldsInsteadOfTheBody()
    {
        $response = Http::request('GET', '/__test/input?k=query', ['Content-Type: application/json'], '{"k":"body"}');
        $this->assertSame(200, $response->status());
        $this->assertSame(['k'=>'query'], $response->json());
    }
}
