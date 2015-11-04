<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

use GreenCape\Robo\Task\Metrics\IgnoredPaths;

class IgnoredPathsTest extends \PHPUnit_Framework_TestCase
{
    private function ignore($tool, $dirs, $files)
    {
        $paths = new IgnoredPaths($dirs, $files);

        return $paths->$tool();
    }

    public function provideTools()
    {
        return [
            [
                'tool'    => 'phpcs',
                'exclude' => [
                    'both'  => ' --ignore=*/bin/*,*/vendor/*,autoload.php,RoboFile.php',
                    'dirs'  => ' --ignore=*/bin/*,*/vendor/*',
                    'files' => ' --ignore=autoload.php,RoboFile.php'
                ]
            ],
            [
                'tool'    => 'pdepend',
                'exclude' => [
                    'both'  => ' --ignore=/bin/,/vendor/,/autoload.php,/RoboFile.php',
                    'dirs'  => ' --ignore=/bin/,/vendor/',
                    'files' => ' --ignore=/autoload.php,/RoboFile.php'
                ]
            ],
            [
                'tool'    => 'phpmd',
                'exclude' => [
                    'both'  => ' --exclude /bin/,/vendor/,/autoload.php,/RoboFile.php',
                    'dirs'  => ' --exclude /bin/,/vendor/',
                    'files' => ' --exclude /autoload.php,/RoboFile.php'
                ]
            ],
            [
                'tool'    => 'phpmetrics',
                'exclude' => [
                    'both'  => ' --excluded-dirs="bin|vendor|autoload.php|RoboFile.php"',
                    'dirs'  => ' --excluded-dirs="bin|vendor"',
                    'files' => ' --excluded-dirs="autoload.php|RoboFile.php"'
                ]
            ],
            [
                'tool'    => 'bergmann',
                'exclude' => [
                    'both'  => ' --exclude=bin --exclude=vendor --exclude=autoload.php --exclude=RoboFile.php',
                    'dirs'  => ' --exclude=bin --exclude=vendor',
                    'files' => ' --exclude=autoload.php --exclude=RoboFile.php'
                ]
            ]
        ];
    }

    /**
     * @dataProvider  provideTools
     * @param         string  $tool
     */
    public function testNoOptionIsGeneratedWhenNothingIsIgnored($tool)
    {
        $this->assertEmpty($this->ignore($tool, '', ' '));
    }

    /**
     * @dataProvider  provideTools
     * @param         string  $tool
     * @param         array   $exclude
     */
    public function testToolsAreProvidedWithTheCorrectSyntaxForFileExclusion($tool, $exclude)
    {
        $this->assertEquals($exclude['both'], $this->ignore($tool, 'bin,vendor', 'autoload.php,RoboFile.php'));
        $this->assertEquals($exclude['dirs'], $this->ignore($tool, 'bin,vendor', ''));
        $this->assertEquals($exclude['files'], $this->ignore($tool, '', 'autoload.php,RoboFile.php'));
    }
}
