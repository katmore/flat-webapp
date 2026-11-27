<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use Composer\InstalledVersions;
class DependencyLockTest extends TestCase
{
    public function testInstalledFrameworkMatchesTheLockedRelease()
    {
        $lock = json_decode(file_get_contents(__DIR__.'/../../composer.lock'), true, 512, JSON_THROW_ON_ERROR);
        $framework = array_values(array_filter($lock['packages'], function ($package) { return $package['name'] === 'katmore/flat'; }));
        $this->assertCount(1, $framework);
        $this->assertMatchesRegularExpression('/^v?1\.74\.\d+$/', $framework[0]['version']);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{40}$/', $framework[0]['source']['reference']);
        $this->assertSame($framework[0]['source']['reference'], InstalledVersions::getReference('katmore/flat'));
        $this->assertSame($framework[0]['version'], InstalledVersions::getPrettyVersion('katmore/flat'));
    }
}
