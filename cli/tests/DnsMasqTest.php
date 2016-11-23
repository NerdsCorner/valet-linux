<?php

use Illuminate\Container\Container;
use Valet\CommandLine;
use Valet\DnsMasq;
use Valet\Filesystem;
use Valet\Linux;

class DnsMasqTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_SERVER['SUDO_USER'] = user();

        Container::setInstance(new Container());
    }

    public function tearDown()
    {
        exec('rm -rf '.__DIR__.'/output');
        mkdir(__DIR__.'/output');
        touch(__DIR__.'/output/.gitkeep');

        Mockery::close();
    }

    public function test_install_installs_and_places_configuration_files_in_proper_locations()
    {
        $ubuntu = Mockery::mock(Linux::class);
        $ubuntu->shouldReceive('ensureInstalled')->once()->with('dnsmasq');
        $ubuntu->shouldReceive('restartService')->once()->with('dnsmasq');
        swap(Linux::class, $ubuntu);

        $dnsMasq = resolve(StubForCreatingCustomDnsMasqConfigFiles::class);

        $dnsMasq->exampleConfigPath = __DIR__.'/files/dnsmasq.conf';
        $dnsMasq->configPath = __DIR__.'/output/dnsmasq.conf';

        $dnsMasq->install('dev');

        $this->assertSame('address=/.dev/127.0.0.1'.PHP_EOL, file_get_contents(__DIR__.'/output/custom-dnsmasq.conf'));
        $this->assertSame('test-contents

conf-file='.__DIR__.'/output/custom-dnsmasq.conf
', file_get_contents(__DIR__.'/output/dnsmasq.conf'));
    }

    // public function test_update_domain_removes_old_resolver_and_reinstalls()
    // {
    //     $cli = Mockery::mock(CommandLine::class);
    //     $cli->shouldReceive('quietly')->with('rm /etc/resolver/old');
    //     $dnsMasq = Mockery::mock(DnsMasq::class.'[install]', [resolve(Ubuntu::class), $cli, new Filesystem]);
    //     $dnsMasq->shouldReceive('install')->with('new');
    //     $dnsMasq->updateDomain('old', 'new');
    // }
}


class StubForCreatingCustomDnsMasqConfigFiles extends DnsMasq
{
    public function customConfigPath()
    {
        return __DIR__.'/output/custom-dnsmasq.conf';
    }
}
