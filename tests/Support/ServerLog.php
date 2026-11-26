<?php
namespace Tests\Support;
final class ServerLog
{
    public static function errors(string $text): array
    {
        $errors = [];
        foreach (preg_split('/\R/', $text) as $line) {
            if (preg_match('/\bPHP (Warning|Notice|Deprecated|Strict Standards|Parse error|Fatal error|Catchable fatal error|Recoverable fatal error|Core error|Core warning|Compile error|Compile warning)\b/i', $line)) {
                $errors[] = $line;
                continue;
            }
            // A truncated application error record is still an error; do not
            // require successful JSON decoding to recognize its type marker.
            if (preg_match('/"type"\s*:\s*"(?:php-error|exception)"/', $line)) {
                $errors[] = $line;
                continue;
            }
            $start = strpos($line, '{'); $end = strrpos($line, '}');
            if ($start === false || $end === false) continue;
            $record = json_decode(substr($line, $start, $end - $start + 1), true);
            if (!is_array($record) || !isset($record['type'], $record['message'])) continue;
            if (in_array($record['type'], ['php-error', 'exception'], true)
                || (is_int($record['type']) && $record['type'] !== 0 && isset($record['file'], $record['line']))) {
                $errors[] = $line;
            }
        }
        return $errors;
    }
}
