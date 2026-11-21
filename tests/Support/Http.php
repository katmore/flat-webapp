<?php
namespace Tests\Support;

/**
 * Sends one real HTTP request to the application's web server and returns the answer.
 * The server's address is FLAT_WEBAPP_URL (docker/bin/test sets it).
 */
final class Http
{
    public static function baseUrl(): string
    {
        $url = getenv('FLAT_WEBAPP_URL');
        if ($url === false || $url === '') {
            throw new \RuntimeException('Set FLAT_WEBAPP_URL to the server address, or run docker/bin/test.');
        }
        return rtrim($url, '/');
    }

    /**
     * @param string[] $headers request headers, each as "Name: value"
     */
    public static function request(string $method, string $path, array $headers = [], string $body = null): Response
    {
        $status = 0;
        $received = [];
        $curl = curl_init(self::baseUrl() . $path);
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 15,
            // no "Expect: 100-continue", so a request body is sent at once
            CURLOPT_HTTPHEADER => array_merge(['Expect:'], $headers),
            CURLOPT_HEADERFUNCTION => function ($curl, string $line) use (&$status, &$received) {
                $length = strlen($line);
                $line = rtrim($line, "\r\n");
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $m)) {
                    $status = (int) $m[1];
                    $received = [];
                } elseif (strpos($line, ':') !== false) {
                    list($name, $value) = explode(':', $line, 2);
                    $received[strtolower($name)][] = trim($value);
                }
                return $length;
            },
        ];
        if ($method === 'HEAD') {
            $options[CURLOPT_NOBODY] = true;
        }
        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }
        curl_setopt_array($curl, $options);
        $content = curl_exec($curl);
        if ($content === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \RuntimeException("$method $path failed: $error");
        }
        curl_close($curl);
        return new Response($status, $received, (string) $content);
    }

    public static function get(string $path, array $headers = []): Response
    {
        return self::request('GET', $path, $headers);
    }
}
