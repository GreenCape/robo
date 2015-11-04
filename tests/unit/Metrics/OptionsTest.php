<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

use GreenCape\Robo\Task\Metrics\Options;

class OptionsTest extends \PHPUnit_Framework_TestCase
{
    private $defaultOptions = [
        'srcDir'       => '.',
        'cfgDir'       => 'build/config',
        'logDir'       => 'build/logs',
        'ignoredDirs'  => 'vendor',
        'ignoredFiles' => '',
        'tools'        => 'phploc,phpcpd,phpcs,pdepend,phpmd,phpmetrics',
        'output'       => 'file',
        'verbose'      => true
    ];

    /** @var  Options  The tested object */
    protected $options;

    public function setUp()
    {
        $this->options = $this->overrideOptions();
    }

    private function overrideOptions(array $options = [])
    {
        return new Options(array_merge($this->defaultOptions, $options));
    }

    public function testPathsAreEscapedProperly()
    {
        $this->assertEquals('"."', $this->options->srcDir);
        $this->assertEquals('"build/logs/file"', $this->options->logFile('file'));
        $this->assertNotEmpty($this->options->cfgFile('file'));
    }

    /**
     * @testdox  PDepend is not executed when CLI output is selected
     */
    public function testPdependIsIgnoredOnCliOutput()
    {
        $cliOutput = $this->overrideOptions([
            'output' => 'cli'
        ]);

        $this->assertTrue($this->options->isEnabled('pdepend'));
        $this->assertFalse($cliOutput->isEnabled('pdepend'));
    }

    public function booleanValues()
    {
        return [[true], [false]];
    }

    /**
     * @testdox       --verbose flag is ignored for CLI output
     * @dataProvider  booleanValues
     * @param         bool $boolean
     */
    public function testVerboseIsIgnoredForCliOutput($boolean)
    {
        $options = $this->overrideOptions([
            'output' => 'cli',
            'verbose' => $boolean
        ]);

        $this->assertTrue($options->isOutputPrinted);
    }

    /**
     * @testdox       --verbose flag is ignored for CLI output
     * @dataProvider  booleanValues
     * @param         bool $boolean
     */
    public function testVerboseIsRespectedForFileOutput($boolean)
    {
        $options = $this->overrideOptions([
            'output' => 'file',
            'verbose' => $boolean
        ]);

        $this->assertEquals($boolean, $options->isOutputPrinted);
    }
}
