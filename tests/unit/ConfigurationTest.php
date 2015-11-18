<?php
namespace GreenCape\RoboTest;

use \GreenCape\Robo\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultIniFileIsReadTransparently()
    {
        $settings = Configuration::get();

        $this->assertArrayHasKey('project.source', $settings);
    }

    public function testIndividualIniFileIsRead()
    {
        Configuration::init(dirname(__DIR__) . '/_data/robo.ini');
        $settings = Configuration::get();

        $this->assertArrayHasKey('project.source', $settings);
        $this->assertEquals('testfile', $settings['project.source']);

        $this->assertArrayHasKey('codestyle.standard', $settings);
        $this->assertEquals('Test', $settings['codestyle.standard']);
    }

    public function testEmptyKeysReturnDefaultValue()
    {
        Configuration::init(dirname(__DIR__) . '/_data/robo.ini');

        $default = 'default value';
        $value   = Configuration::get('codestyle.ignore', $default);

        $this->assertEquals($default, $value);
    }
}
