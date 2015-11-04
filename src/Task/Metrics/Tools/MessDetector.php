<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class MessDetector extends AbstractTool
{
    /**
     * phpmd usage:
     *
     * Mandatory arguments:
     * 1) A php source code filename or directory. Can be a comma-separated string
     * 2) A report format
     * 3) A ruleset filename or a comma-separated string of rulesetfilenames
     *
     * Available formats: xml, text, html.
     * Available rulesets: cleancode, codesize, controversial, design, naming, unusedcode.
     *
     * Optional arguments that may be put after the mandatory arguments:
     * --minimumpriority: rule priority threshold; rules with lower priority than this will not be used
     * --reportfile: send report output to a file; default to STDOUT
     * --suffixes: comma-separated string of valid source code filename extensions
     * --exclude: comma-separated string of patterns that are used to ignore directories
     * --strict: also report those nodes with a @SuppressWarnings annotation
     *
     * @return array
     */
    public function getArgs()
    {
        if ($this->options->showVersion) {
            return ['verbose' => ''];
        }

        $args = [
            $this->options->srcDir,
            $this->options->isSavedToFiles ? 'xml' : 'text',
            $this->options->cfgFile('phpmd.xml'),
            'sufixxes' => 'php',
            $this->options->ignore->phpmd()
        ];
        if ($this->options->isSavedToFiles) {
            $args['reportfile'] = $this->options->logFile('phpmd.xml');
        }

        return $args;
    }

    protected function getOptionSeparator()
    {
        return ' ';
    }

    protected function getBinary()
    {
        return PROJECT_ROOT . '/vendor/bin/phpmd';
    }
}
