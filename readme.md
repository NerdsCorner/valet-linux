# Laravel Valet *for Linux*

[![Build Status](https://travis-ci.org/jmarcher/valet-linux.svg?branch=master)](https://travis-ci.org/jmarcher/valet-linux)
[![Latest Stable Version](https://poser.pugx.org/jmarcher/valet-linux/v/stable)](https://packagist.org/packages/jmarcher/valet-linux)
[![Total Downloads](https://poser.pugx.org/jmarcher/valet-linux/downloads)](https://packagist.org/packages/jmarcher/valet-linux)
[![Latest Unstable Version](https://poser.pugx.org/jmarcher/valet-linux/v/unstable)](https://packagist.org/packages/jmarcher/valet-linuxu)
[![License](https://poser.pugx.org/jmarcher/valet-linux/license)](https://packagist.org/packages/jmarcher/valet-linux)

## Introduction

Valet *for Linux* is a Laravel development environment for Linux minimalists. No Vagrant, No Apache, No Nginx, No `/etc/hosts` file. You can even share your sites publicly using local tunnels. _Yeah, we like it too._

Laravel Valet *for Linux* configures your system to always run [Caddy](https://caddyserver.com/) in the background when your machine starts. Then, using [DnsMasq](https://en.wikipedia.org/wiki/Dnsmasq), Valet proxies all requests on the `*.dev` domain to point to sites installed on your local machine.

In other words, a blazing fast Laravel development environment that uses roughly 7mb of RAM. Valet *for Linux* isn't a complete replacement for Vagrant or Homestead, but provides a great alternative if you want flexible basics, prefer extreme speed, or are working on a machine with a limited amount of RAM.

## Supported Linux Distributions
    - Ubuntu and Derivates like: Linux Mint, elementaryOS, ZorinOS, etc
    - Arch Linux and Derivates like: Manjaro, Astergos, etc
    - Fedora and near Derivates

## Official Documentation

Documentation for Valet can be found on the [Laravel website](http://laravel.com/docs/5.2/valet).

## Requirements

 - PHP >= 7.0
 - PHP Packages: `php*-cli php*-common php*-curl php*-json php*-mbstring php*-mcrypt php*-mysql php*-opcache php*-readline php*-xml php*-zip`
 - Optional PHP Packages: `php*-sqlite3 php*-mysql php*-pgsql`

**Replace the star _(*)_ with your php version**

## Installation

1. `composer global require jmarcher/valet-linux`
2. `valet install`

## Caveats

Because of the way Firefox and Chrome/Chromium/Opera/Any.Other.Blink.Based.Browser manages certificates in Linux the experience when **securing** a site might not be as smooth as it is in OSX.

Whenever you secure a site you'll need to restart your testing browser so that it can trust the new certificate and you'll have to do the same when you unsecure it.

If you have **secured** a domain you will not be able to share it through Ngrok.

## Usage

**`valet park`**

You can use `valet park` inside the directory where you store your projects (like Sites or Code) and then you can open `http://projectname.dev` in your browser. This command will allow you to access all the projects in the *parked* folder.

**`valet link`**

If you just want to serve a single site you can use `valet link [your-desired-url]` and then open `http://your-desired-url.dev` in the browser.

**`valet status`**

To check the status of the **Valet _for Linux_** services.

## Update

To update your Valet package just run: `composer global update`

## F.A.Q.

**Having problems with .dev domains not pointing to 127.0.0.1?**
Try this: Thanks to @adriaanzon for the guide
Comment out this line in `/etc/NetworkManager/NetworkManager.conf`:

```
dns=default
```

Uncomment this line in `/etc/resolvconf.conf`

```
#name_servers=127.0.0.1
```

Append this to `/etc/dnsmasq.conf`

```
no-resolv
server=8.8.8.8
server=8.8.4.4
```


**Why is my preferred distribution not supported?**

Well, not all distros work the same way, we need to test every distribution in order to release it to the public and also
know how every distribution works.

If you have experience with your distribution and also a bit of experience with PHP (you don't need too much experience),
write a new Issue, and tell me which distro you want to add.

**Why can't I run `valet install`?**

Check that you've added the `.composer/vendor/bin` directory to your `PATH` in either `~/.bashrc` or `~/.zshrc`.

**What about the Database?**

Well, your choice! You could use the superlight SQLite **`sqlite3`**, the extremely versatile MariaDB/MySQL **`mariadb-server or mysql-server`** or even the powerful PostgreSQL **`postgresql`**. Just don't forget to install the corresponding php package for it.

**Any other tips?**

Oh yeah!, for those looking for a beautiful looking Database management tool like Sequel Pro but for Linux* try out Valentina Studio, it's free, multiplatform and supports all of the databases mentioned above.

[You can check it here](https://www.valentina-db.com/en/valentina-studio-overview)

[And download it here](https://www.valentina-db.com/en/studio/download)

_* I know it is GNU/Linux but is too long and it confuses people even more_

## License

Laravel Valet is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
