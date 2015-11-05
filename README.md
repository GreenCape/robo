# robo

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
  help               Displays help for a command
  list               Lists commands
  metrics            
  test               
 metrics
  metrics:codestyle  
  metrics:versions   Report the versions of the available tools
 test
  test:acceptance    
  test:cli           
  test:functional    
  test:integration   
  test:system        
  test:unit          
```
