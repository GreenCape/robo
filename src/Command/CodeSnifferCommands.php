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

trait CodeSnifferCommands
{
    use Configuration;
    use Timer;
    use IO;
    use \GreenCape\Robo\Task\CodeSniffer\loadTasks;

    /**
     * @param       $source
     * @param array $options
     * @option $standard [Path to] the standard to be checked for
     */
    public function checkCodestyle(
        $source = null,
        $options = [
            'standard' => null
        ]
    )
    {

        if (empty($source)) {
            $source = $this->getConfigValue('project.source', PROJECT_ROOT);
        }

        $defaults = [
            'files' => $source,
            'standard' => $this->getConfigValue('codestyle.standard', 'PSR2'),
            'ignored' => '',
            'showProgress' => true,
            'verbosity'    => true,
            'report-width' => '150',
            'ignoreErrors' => true
        ];
        $options  = array_merge($defaults, array_filter($options));

        $phpcs = new \PHP_CodeSniffer_CLI;
        $phpcs->checkRequirements();
        $numErrors = $phpcs->process($options);

        $this->say("Found $numErrors errors");
    }

    /**
     * Generate documentation for a coding standard.
     *
     * @todo Provide template documentation
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

    public function fixCodestyle()
    {
        $this->runCodeSniffer([

        ]);
    }

    public function metricsCodestyle()
    {
        (new CodeStyle)
            ->inspect('src')
            ->standard('PSR2')
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
            array_walk($standards, function(&$value) {
                $value = "<fg=green>$value</fg=green>";
            });
        }
        $last = array_pop($standards);
        $this->say("Installed coding standards are " . implode(', ', $standards) . " and " . $last);
    }

    private function runCodeSniffer($options)
    {
        $this->startTimer();
        $phpcs = new \PHP_CodeSniffer_CLI;
        $phpcs->checkRequirements();
        $numErrors = $phpcs->process($options);
        $this->stopTimer();

        return $numErrors;
    }
}
