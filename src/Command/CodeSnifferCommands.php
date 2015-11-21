<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Command;

use Robo\Common\Configuration;
use Robo\Common\IO;
use Robo\Common\Timer;

/**
 * Trait CodeSnifferCommands
 *
 * @package GreenCape\Robo\Command
 */
trait CodeSnifferCommands
{
    use Configuration;
    use Timer;
    use IO;
    use \GreenCape\Robo\Task\CodeSniffer\loadTasks;

    /**
     * Generate documentation for a coding standard.
     *
     * See https://github.com/GreenCape/robo/tree/master/docs/codestyle-template.md
     * for information about how to build a custom template.
     *
     * @param array $options
     * @option $standard [Path to] the standard to be documented
     * @option $template Path to a Twig template to use for rendering
     * @option $outfile Path to the output file
     */
    public function documentCodestyle(
        $options = [
            'standard' => null,
            'template' => null,
            'outfile'  => null
        ]
    ) {
        $this->taskCodeSnifferDocument()
            ->standard($options['standard'])
            ->template($options['template'])
            ->outfile($options['outfile'])
            ->run();
    }

    /**
     * Show the available coding standards
     *
     * @param array $options
     */
    public function showCodestyleStandards($options = [])
    {
        $phpcs = new \PHP_CodeSniffer;
        $standards = $phpcs->getInstalledStandards();
        sort($standards);
        if (!$options['no-ansi']) {
            array_walk($standards, function (&$value) {
                $value = "<fg=green>$value</fg=green>";
            });
        }
        $last = array_pop($standards);
        $this->say("Installed coding standards are " . implode(', ', $standards) . " and " . $last);
    }
}
