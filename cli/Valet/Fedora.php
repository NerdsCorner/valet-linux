<?php
/**
 * Created by PhpStorm.
 * User: sunznx
 * Date: 2017-01-17
 * Time: 23:03:20.
 */

namespace Valet;

use DomainException;
use Valet\Contracts\LinuxContract;

class Fedora implements LinuxContract
{
    public $cli;
    public $files;

    /**
     * Arch constructor.
     *
     * @param CommandLine $cli
     * @param Filesystem  $files
     */
    public function __construct(CommandLine $cli, Filesystem $files)
    {
        $this->cli = $cli;
        $this->files = $files;
    }

    public function installed(string $package) : bool
    {
        return strcmp(substr($this->cli->run('sudo rpm -q '.$package), 0, strlen($package)), $package) === 0;
    }

    public function installOrFail(string $package)
    {
        output('<info>['.$package.'] is not installed, installing it now...</info> 🍻');

        $this->cli->run('sudo dnf install -y '.$package, function ($errorOutput) use ($package) {
            output($errorOutput);

            throw new DomainException('Unable to install ['.$package.'].');
        });
    }

    public function restartService($services)
    {
        $services = is_array($services) ? $services : func_get_args();

        foreach ($services as $service) {
            $this->cli->quietly('sudo systemctl restart '.$service);
        }
    }

    public function stopService($services)
    {
        $services = is_array($services) ? $services : func_get_args();

        foreach ($services as $service) {
            $this->cli->quietly('sudo systemctl stop '.$service);
        }
    }

    /**
     * Enables the given Homebrew services.
     *
     * @param
     */
    public function enableService($services)
    {
        $services = is_array($services) ? $services : func_get_args();

        foreach ($services as $service) {
            $this->cli->quietly('sudo systemctl enable '.$service);
        }
    }

    /**
     * Enables the given Homebrew services.
     *
     * @param
     */
    public function disableService($services)
    {
        $services = is_array($services) ? $services : func_get_args();

        foreach ($services as $service) {
            $this->cli->quietly('sudo systemctl disable '.$service);
        }
    }

    public function linkedPhp() :string
    {
        return 'php';
        /*if (!$this->files->isLink($this->getConfig('php-bin'))) {
            throw new DomainException('Unable to determine linked PHP.');
        }*/

        $resolvedPath = $this->files->readLink($this->getConfig('php-bin'));

        if (strpos($resolvedPath, $this->getConfig('php-latest')) !== false) {
            return $this->getConfig('php-latest');
        } elseif (strpos($resolvedPath, $this->getConfig('php-56')) !== false) {
            return $this->getConfig('php-56');
        } elseif (strpos($resolvedPath, $this->getConfig('php-55')) !== false) {
            return $this->getConfig('php-55');
        } else {
            throw new DomainException('Unable to determine linked PHP.');
        }
    }

    public function getConfig(string $value):string
    {
        $config = [
            // PHP binary path
            'php-bin' => '/usr/bin/php',

            // Latest PHP
            'php-latest'  => 'php',
            'fpm-service' => 'php-fpm',
            'fpm-config'  => '/etc/php-fpm.d/www.conf',

            // Caddy/Systemd
            'systemd-caddy'     => '/lib/systemd/system/caddy.service',
            'systemd-caddy-fpm' => '/var/run/php-fpm/php-fpm.sock',

            // PHP 5.6
            'php-56'        => 'php56-php-fpm',
            'fpm56-service' => 'php56-php-fpm',
            'fpm56-config'  => '/opt/remi/php56/root/etc/php-fpm.conf',

            'php-55'        => 'php55-php-fpm',
            'fpm55-service' => 'php55-php-fpm',
            'fpm55-config'  => '/opt/remi/php55/root/etc/php-fpm.conf',

            'network-manager' => 'NetworkManager',
        ];

        return $config[$value];
    }
}
