<?php
namespace GreenCape\RoboTest;

use GreenCape\Robo\Common\ShellCommand;

class ShellCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleOptionIsSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->option('bar')
            ->getCommand();

        $this->assertEquals('foo --bar', $command);
    }

    public function testSingleOptionWithValueIsSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->option('bar', 'baz')
            ->getCommand();

        $this->assertEquals('foo --bar=baz', $command);
    }

    public function testSingleValueIsSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->option(null, 'bar')
            ->getCommand();

        $this->assertEquals('foo bar', $command);
    }

    public function testMultipleOptionsAreSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->options([
                'value1',
                'option2' => 'value2',
                'option3' => ''
            ])
            ->getCommand();

        $this->assertEquals('foo value1 --option2=value2 --option3', $command);
    }

    public function testSingleArgumentIsSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->arg('any string')
            ->getCommand();

        $this->assertEquals('foo any string', $command);
    }

    public function testMultipleArgumentsAreSetProperly()
    {
        $command = (new ShellCommand('foo', '='))
            ->args([
                '--value1',
                'option2' => 'value2',
                'option3' => ''
            ])
            ->getCommand();

        $this->assertEquals('foo --value1 value2', $command);
    }

    public function testArgumentListCanBeReset()
    {
        $command = (new ShellCommand('foo', '='))
            ->args([
                '--value1',
                'option2' => 'value2',
                'option3' => ''
            ])
            ->resetArgs()
            ->getCommand();

        $this->assertEquals('foo', $command);
    }

    public function testVersionInformationIsPrinted()
    {
        ob_start();
        $result = (new ShellCommand('vendor/bin/phpcs', '=', '--version'))
            ->version();
        $output = ob_get_clean();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals($output, $result->getMessage());
        $this->assertRegExp('~version.*?\d+.\d+.\d+~i', $output);
    }
}
