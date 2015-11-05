<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Command;

trait TestCommands
{
    use \Robo\Task\Testing\loadTasks;

    public function test()
    {
        foreach (['unit', 'cli', 'functional', 'acceptance'] as $suite) {
            if ($this->hasContent("tests/{$suite}")) {
                $method = 'test' . ucfirst($suite);
                $this->{$method}();
            }
        }
    }

    public function testUnit()
    {
        $this->taskCodecept()
             ->suite('unit')
             ->run();
    }

    public function testCli()
    {
        $this->taskCodecept()
             ->suite('cli')
             ->run();
    }

    public function testIntegration()
    {
        $this->testFunctional();
    }

    public function testFunctional()
    {
        $this->taskCodecept()
             ->suite('functional')
             ->run();
    }

    public function testSystem()
    {
        $this->testAcceptance();
    }

    public function testAcceptance()
    {
        $this->taskCodecept()
             ->suite('acceptance')
             ->run();
    }

    private function hasContent($dir)
    {
        foreach (glob("{$dir}/*") as $candidate) {
            if (is_dir($candidate)) {
                if ($this->hasContent($candidate)) {
                    return true;
                }

                continue;
            }
            if (!preg_match('~bootstrap\.php$~', $candidate)) {
                return true;
            }
        }

        return false;
    }
}
