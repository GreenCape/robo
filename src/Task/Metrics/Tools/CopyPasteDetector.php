<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class CopyPasteDetector extends AbstractTool
{
    /**
     * phpcpd usage:
     *
     * phpcpd [options] [--] [<values>]...
     *    values:
     *        Files and directories to analyze
     *    options:
     *        --names=NAMES                  A comma-separated list of file names to check [default: ["*.php"]]
     *        --names-exclude=NAMES-EXCLUDE  A comma-separated list of file names to exclude
     *        --exclude=EXCLUDE              Exclude a directory from code analysis (must be relative to source) (multiple values allowed)
     *        --log-pmd=LOG-PMD              Write result in PMD-CPD XML format to file
     *        --min-lines=MIN-LINES          Minimum number of identical lines [default: 5]
     *        --min-tokens=MIN-TOKENS        Minimum number of identical tokens [default: 70]
     *        --fuzzy                        Fuzz variable names
     *        --progress                     Show progress bar
     *
     * @return array
     */
    public function getArgs()
    {
        if ($this->options->showVersion) {
            return ['verbose' => ''];
        }

        $args   = [];
        $args[] = $this->options->ignore->bergmann();

        if ($this->options->isSavedToFiles) {
            $args['log-pmd'] = $this->options->logFile('phpcpd.xml');
        }

        $args[] = $this->options->srcDir;

        return $args;
    }

    protected function getOptionSeparator()
    {
        return ' ';
    }

    protected function getBinary()
    {
        return PROJECT_ROOT . '/vendor/bin/phpcpd';
    }
}
