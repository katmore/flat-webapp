<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use Tests\Support\ServerLog;
class ErrorPathsTest extends TestCase
{
    /** @dataProvider errors */
    public function testExpectedErrorsHaveExactFailureContracts($mode, $status, $body, $fragment, $httpStatus)
    {
        $report = tempnam(sys_get_temp_dir(), 'webapp-error-');
        $script = __DIR__.'/../fixtures/error-path.php';
        $command = escapeshellarg(PHP_BINARY).' -d display_errors=0 -d log_errors=1 '.escapeshellarg($script).' '.escapeshellarg($mode).' '.escapeshellarg($report);
        try {
            $process = proc_open($command, [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes);
            $this->assertIsResource($process); fclose($pipes[0]);
            $out = stream_get_contents($pipes[1]); fclose($pipes[1]);
            $err = stream_get_contents($pipes[2]); fclose($pipes[2]);
            $this->assertSame($status, proc_close($process), $out.$err);
            $this->assertSame($body, $out);
            $this->assertStringContainsString($fragment, $err);
            $this->assertSame(['status'=>$httpStatus], json_decode(file_get_contents($report), true));
            $this->assertCount(1, ServerLog::errors($err));
            if ($status === 1) {
                $record = json_decode(trim($err), true, 512, JSON_THROW_ON_ERROR);
                $this->assertSame($fragment, $record['message']);
                if ($mode === 'handled-fatal') $this->assertSame(E_USER_ERROR, $record['errno']);
                else $this->assertSame('RuntimeException', $record['name']);
            } else {
                $this->assertStringContainsString('PHP Fatal error:', $err);
            }
        } finally { unlink($report); }
    }
    public function errors()
    {
        return [
            ['handled-fatal',1,'Unable to process this request','review fatal canary',500],
            ['handled-exception',1,'Unable to process this request','review exception canary',500],
            ['malformed-json',255,'','malformed JSON in request document',false],
            ['patch',255,'','unknown_method',false],
        ];
    }
}
