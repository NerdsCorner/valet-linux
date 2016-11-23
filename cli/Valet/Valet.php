<?php

namespace Valet;

use Marcher\FluentCurl\FluentCurl;

class Valet
{
    public $cli;
    public $files;

    public $valetBin = '/usr/local/bin/valet';

    /**
     * Create a new Valet instance.
     *
     * @param CommandLine $cli
     * @param Filesystem  $files
     */
    public function __construct(CommandLine $cli, Filesystem $files)
    {
        $this->cli = $cli;
        $this->files = $files;
    }

    /**
     * Symlink the Valet Bash script into the user's local bin.
     *
     * @return void
     */
    public function symlinkToUsersBin()
    {
        $this->cli->quietly('rm '.$this->valetBin);

        $this->cli->run('ln -s '.realpath(__DIR__.'/../../valet').' '.$this->valetBin);
    }

    /**
     * Create the "sudoers.d" entry for running Valet.
     *
     * @return void
     */
    public function createSudoersEntry()
    {
        $this->files->ensureDirExists('/etc/sudoers.d');

        $this->files->put('/etc/sudoers.d/valet', 'Cmnd_Alias VALET = /usr/local/bin/valet *
%sudo ALL=(root) NOPASSWD: VALET'.PHP_EOL);

        $this->cli->quietly('chmod 0440 /etc/sudoers.d/valet');
    }

    /**
     * Get the paths to all of the Valet extensions.
     *
     * @return array
     */
    public function extensions()
    {
        if (!$this->files->isDir(VALET_HOME_PATH.'/Extensions')) {
            return [];
        }

        return collect($this->files->scandir(VALET_HOME_PATH.'/Extensions'))
                    ->reject(function ($file) {
                        return is_dir($file);
                    })
                    ->map(function ($file) {
                        return VALET_HOME_PATH.'/Extensions/'.$file;
                    })
                    ->values()->all();
    }

    /**
     * Determine if this is the latest version of Valet.
     *
     * @param string $currentVersion
     *
     * @return bool
     */
    public function onLatestVersion($currentVersion)
    {
        $response = (new FluentCurl())->setUrl('https://api.github.com/repos/jmarcher/valet-linux/releases/latest')->execute();

        return version_compare($currentVersion, trim($response->body->tag_name, 'v'), '>=');
    }
}
