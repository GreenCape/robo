<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class LineCounter extends AbstractTool
{
    /**
     * phploc usage:
     *
     * phploc [options] [--] [<values>]...
     *    values:
     *        Files and directories to analyze
     *    options:
     *       --names=NAMES                    A comma-separated list of file names to check [default: ["*.php"]]
     *       --names-exclude=NAMES-EXCLUDE    A comma-separated list of file names to exclude
     *       --count-tests                    Count PHPUnit test case classes and test methods
     *       --git-repository=GIT-REPOSITORY  Collect metrics over the history of a Git repository
     *       --exclude=EXCLUDE                Exclude a directory from code analysis (multiple values allowed)
     *       --log-csv=LOG-CSV                Write result in CSV format to file
     *       --log-xml=LOG-XML                Write result in XML format to file
     *       --progress                       Show progress bar
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
            $args['log-xml'] = $this->options->logFile('phploc.xml');
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
        return PROJECT_ROOT . '/vendor/bin/phploc';
    }
}
