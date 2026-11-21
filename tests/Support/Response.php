<?php
namespace Tests\Support;

/**
 * What the server answered: status code, headers and body.
 */
final class Response
{
    private $status;
    private $headers;
    private $body;

    public function __construct(int $status, array $headers, string $body)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function status(): int
    {
        return $this->status;
    }

    /** The first value of a header (the name is case-insensitive), or null when it was not sent. */
    public function header(string $name): ?string
    {
        $values = $this->headers[strtolower($name)] ?? [];
        return $values === [] ? null : $values[0];
    }

    public function body(): string
    {
        return $this->body;
    }

    /** The body decoded as JSON; invalid JSON throws, so the test fails. */
    public function json()
    {
        return json_decode($this->body, true, 512, JSON_THROW_ON_ERROR);
    }
}
