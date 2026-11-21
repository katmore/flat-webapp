<?php
namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Tests\Support\Http;

/**
 * The HTML side: web/view.php, routed to a template or to the not-found page.
 */
class ViewTest extends TestCase
{
    const HTML = 'text/html; charset=utf-8';

    /** @dataProvider homePaths */
    public function testTheHomeViewAnswersHtml(string $path)
    {
        $response = Http::get($path);
        $this->assertSame(200, $response->status());
        $this->assertSame(self::HTML, $response->header('Content-Type'));
        $this->assertStringContainsString('<title>Home - WebApp Example</title>', $response->body());
        $this->assertStringContainsString('<h1>My Web App</h1>', $response->body());
        $this->assertStringContainsString('data-viewBody="home"', $response->body());
    }

    public function homePaths()
    {
        return [
            'no path' => ['/view.php'],
            'root' => ['/view.php/'],
            'home' => ['/view.php/home'],
            'trailing slash' => ['/view.php/home/'],
            'doubled slashes' => ['//view.php//home'],
            'query string' => ['/view.php/home?ignored=1'],
            'under /webapp' => ['/webapp/view.php/home'],
        ];
    }

    /** @dataProvider unknownPaths */
    public function testAnUnknownViewAnswersTheNotFoundPage(string $path)
    {
        $response = Http::get($path);
        $this->assertSame(404, $response->status());
        $this->assertSame(self::HTML, $response->header('Content-Type'));
        $this->assertStringContainsString('<title>Not Found</title>', $response->body());
        $this->assertStringNotContainsString('<h1>My Web App</h1>', $response->body());
    }

    public function unknownPaths()
    {
        return [
            'unknown view' => ['/view.php/unknown'],
            'unknown, deeper' => ['/view.php/unknown/deeper'],
        ];
    }

    public function testTheRequestMethodDoesNotChangeTheView()
    {
        $post = Http::request('POST', '/view.php/home', ['Content-Type: application/x-www-form-urlencoded'], 'a=1');
        $this->assertSame(200, $post->status());
        $this->assertStringContainsString('<h1>My Web App</h1>', $post->body());
    }

    public function testHeadSendsTheHeadersAndNoBody()
    {
        $head = Http::request('HEAD', '/view.php/home');
        $this->assertSame(200, $head->status());
        $this->assertSame(self::HTML, $head->header('Content-Type'));
        $this->assertSame('', $head->body());
    }

    /** @dataProvider stylesheetPaths */
    public function testTheStylesheetIsServed(string $path)
    {
        $response = Http::get($path);
        $this->assertSame(200, $response->status());
        $this->assertSame('text/css', $response->header('Content-Type'));
        $this->assertStringContainsString('font-family', $response->body());
    }

    public function stylesheetPaths()
    {
        return [['/asset/css/custom.css'], ['/webapp/asset/css/custom.css']];
    }
}
