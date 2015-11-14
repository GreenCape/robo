# GreenCape/robo

A collection of Robo.li tasks, mainly for use in GreenCape/build

> **CAVEAT:** This project is in a very early stadium and far from complete.
> Use it on your own risk.

## Installation

```sh
$ composer require greencape/robo
```

Add the local `vendor` directory to your path, if not already done so:

```sh
$ export PATH="./vendor/bin:$PATH"
```

To make this change permanent, add the line to your `~.bashrc` file.

Add the new commands to your `RoboFile.php` in your project's root:

```php
define('PROJECT_ROOT', __DIR__);

class RoboFile extends \Robo\Tasks
{
    use \GreenCape\Robo\Command\MetricsCommands;
    use \GreenCape\Robo\Command\TestCommands;

    // Your own/local tasks go here
}
```

Now you can list the available commands:

```sh
$ robo list
```
```
Robo version 0.6.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  configure           
  help                Displays help for a command
  list                Lists commands
  test                Run all available tests
 check
  check:codestyle     
 document
  document:codestyle  Generate documentation for a coding standard.
 fix
  fix:codestyle       
 metrics
  metrics:codestyle   
 show
  show:config         Show the current configuration settings
 test
  test:acceptance     Run the acceptance (system) tests
  test:cli            Run the command line tests
  test:functional     Run the functional (integration) tests
  test:integration    Alias for test:functional
  test:system         Alias for test:acceptance
  test:unit           Run the unit tests
```
