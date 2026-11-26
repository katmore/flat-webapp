<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use Tests\Support\ServerLog;
class ServerLogTest extends TestCase
{
    /** @dataProvider phpMessages */
    public function testNativeMessagesFailTheLogGate($kind)
    {
        $line = 'web-1 | [date] PHP '.$kind.': review canary';
        $this->assertSame([$line], ServerLog::errors($line));
    }
    public function phpMessages()
    {
        return array_map(function ($kind) { return [$kind]; }, ['Warning','Notice','Deprecated','Strict Standards',
            'Parse error','Fatal error','Catchable fatal error','Recoverable fatal error','Core error','Compile warning']);
    }
    /** @dataProvider structuredErrors */
    public function testApplicationErrorRecordsFailTheLogGate($record)
    {
        $line = 'web-1 | [php7:notice] [pid 10] '.json_encode($record);
        $this->assertSame([$line], ServerLog::errors($line));
    }
    public function structuredErrors()
    {
        return [
            [['type'=>'php-error','errno'=>E_USER_ERROR,'message'=>'fatal']],
            [['type'=>'exception','name'=>'RuntimeException','message'=>'failure']],
            [['type'=>E_ERROR,'message'=>'fatal','file'=>'fixture.php','line'=>1]],
        ];
    }
    public function testOrdinaryAccessAndStartupLogsPass()
    {
        $this->assertSame([], ServerLog::errors("[core:notice] Apache started\nGET /api.php/example 200\n{not JSON}\n{\"message\":\"business event\"}"));
    }
    public function testTruncatedApplicationErrorsStillFail()
    {
        $line = 'web-1 | {"type":"exception","message":"truncated';
        $this->assertSame([$line], ServerLog::errors($line));
    }
    public function testCommandRejectsAnErrorAndAcceptsACleanLog()
    {
        $file = tempnam(sys_get_temp_dir(), 'webapp-log-');
        try {
            foreach ([['{"type":"exception","message":"canary"}',1], ['Apache started',0]] as $case) {
                file_put_contents($file, $case[0]);
                $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg(__DIR__.'/../tools/check-server-log.php').' '.escapeshellarg($file);
                $process = proc_open($command, [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes);
                $this->assertIsResource($process); fclose($pipes[0]);
                $out = stream_get_contents($pipes[1]); fclose($pipes[1]);
                $err = stream_get_contents($pipes[2]); fclose($pipes[2]);
                $this->assertSame($case[1], proc_close($process));
                $this->assertSame('', $out);
                $this->assertSame($case[1] ? $case[0]."\n" : '', $err);
            }
        } finally { unlink($file); }
    }
    public function testCommandNamesTheProblemWhenTheLogCannotBeRead()
    {
        $script = escapeshellarg(PHP_BINARY).' '.escapeshellarg(__DIR__.'/../tools/check-server-log.php');
        foreach ([[$script.' '.escapeshellarg(sys_get_temp_dir().'/webapp-log-missing-'.bin2hex(random_bytes(4))), 'Cannot read the log file'],
                  [$script, 'Usage: php tests/tools/check-server-log.php LOG_FILE']] as $case) {
            $process = proc_open($case[0], [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes);
            $this->assertIsResource($process); fclose($pipes[0]);
            stream_get_contents($pipes[1]); fclose($pipes[1]);
            $err = stream_get_contents($pipes[2]); fclose($pipes[2]);
            $this->assertSame(2, proc_close($process));
            $this->assertStringContainsString($case[1], $err);
            if ($case[1] === 'Cannot read the log file') $this->assertStringNotContainsString('Usage', $err);
        }
    }
}
