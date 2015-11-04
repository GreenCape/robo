<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class CodeSniffer extends AbstractTool
{
    /**
     * phpcs usage:
     *
     * phpcs [-nwlsaepvi] [-d key[=value]] [--colors] [--no-colors]
     * [--report=<report>] [--report-file=<reportFile>] [--report-<report>=<reportFile>] ...
     * [--report-width=<reportWidth>] [--generator=<generator>] [--tab-width=<tabWidth>]
     * [--severity=<severity>] [--error-severity=<severity>] [--warning-severity=<severity>]
     * [--runtime-set key value] [--config-set key value] [--config-delete key] [--config-show]
     * [--standard=<standard>] [--sniffs=<sniffs>] [--encoding=<encoding>]
     * [--extensions=<extensions>] [--ignore=<patterns>] <file> ...
     * Set runtime value (see --config-set)
     * -n            Do not print warnings (shortcut for --warning-severity=0)
     * -w            Print both warnings and errors (this is the default)
     * -l            Local directory only, no recursion
     * -s            Show sniff codes in all reports
     * -a            Run interactively
     * -e            Explain a standard by showing the sniffs it includes
     * -p            Show progress of the run
     * -v[v][v]      Print verbose output
     * -i            Show a list of installed coding standards
     * -d            Set the [key] php.ini value to [value] or [true] if value is omitted
     * --help        Print this help message
     * --version     Print version information
     * --colors      Use colors in output
     * --no-colors   Do not use colors in output (this is the default)
     * <file>        One or more files and/or directories to check
     * <encoding>    The encoding of the files being checked (default is iso-8859-1)
     * <extensions>  A comma separated list of file extensions to check
     *               (extension filtering only valid when checking a directory)
     *               The type of the file can be specified using: ext/type
     *               e.g., module/php,es/js
     * <generator>   The name of a doc generator to use
     *               (forces doc generation instead of checking)
     * <patterns>    A comma separated list of patterns to ignore files and directories
     * <report>      Print either the "full", "xml", "checkstyle", "csv"
     *               "json", "emacs", "source", "summary", "diff"
     *               "svnblame", "gitblame", "hgblame" or "notifysend" report
     *               (the "full" report is printed by default)
     * <reportFile>  Write the report to the specified file path
     * <reportWidth> How many columns wide screen reports should be printed
     *               or set to "auto" to use current screen width, where supported
     * <sniffs>      A comma separated list of sniff codes to limit the check to
     *               (all sniffs must be part of the specified standard)
     * <severity>    The minimum severity required to display an error or warning
     * <standard>    The name or path of the coding standard to use
     * <tabWidth>    The number of spaces each tab represents
     *
     * @return array
     */
    public function getArgs()
    {
        if ($this->options->showVersion) {
            return ['verbose' => ''];
        }

        $args = [
            'extensions' => 'php',
            'standard'   => 'PSR2',
            $this->options->ignore->phpcs(),
        ];

        if ($this->options->isSavedToFiles) {
            $args['report']      = 'checkstyle';
            $args['report-file'] = $this->options->logFile('checkstyle.xml');
        } else {
            $args['report'] = 'full';
        }

        $args[] = $this->options->srcDir;

        return $args;
    }

    protected function getBinary()
    {
        return PROJECT_ROOT . '/vendor/bin/phpcs';
    }
}
