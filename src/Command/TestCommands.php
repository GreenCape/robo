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

    /**
     * Run all available tests
     */
    public function test()
    {
        foreach (['unit', 'cli', 'functional', 'acceptance'] as $suite) {
            if ($this->hasContent("tests/{$suite}")) {
                $method = 'test' . ucfirst($suite);
                $this->{$method}();
            }
        }
    }

    /**
     * Run the unit tests
     */
    public function testUnit()
    {
        $this->taskCodecept()
             ->suite('unit')
             ->run();
    }

    /**
     * Run the command line tests
     */
    public function testCli()
    {
        $this->taskCodecept()
             ->suite('cli')
             ->run();
    }

    /**
     * Alias for test:functional
     */
    public function testIntegration()
    {
        $this->testFunctional();
    }

    /**
     * Run the functional (integration) tests
     */
    public function testFunctional()
    {
        $this->taskCodecept()
             ->suite('functional')
             ->run();
    }

    /**
     * Alias for test:acceptance
     */
    public function testSystem()
    {
        $this->testAcceptance();
    }

    /**
     * Run the acceptance (system) tests
     */
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
