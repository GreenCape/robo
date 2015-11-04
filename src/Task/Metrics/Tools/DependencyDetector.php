<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class DependencyDetector extends AbstractTool
{
    /**
     * pdepend usage:
     *
     * pdepend [options] [logger] <dir[,dir[,...]]>
     *
     * --jdepend-chart=<file>    Generates a diagram of the analyzed packages.
     * --jdepend-xml=<file>      Generates the package dependency log.
     * --overview-pyramid=<file> Generates a chart with an Overview Pyramid for the
     *                           analyzed project.
     * --summary-xml=<file>      Generates a xml log with all metrics.
     * --coderank-mode=<*[,...]> Used CodeRank strategies. Comma separated list of
     *                           'inheritance'(default), 'property' and 'method'.
     * --coverage-report=<file>  Clover style CodeCoverage report, as produced by
     *                           PHPUnit's --coverage-clover option.
     * --configuration=<file>    Optional PDepend configuration file.
     * --suffix=<ext[,...]>      List of valid PHP file extensions.
     * --ignore=<dir[,...]>      List of exclude directories.
     * --exclude=<pkg[,...]>     List of exclude namespaces.
     * --without-annotations     Do not parse doc comment annotations.
     * --debug                   Prints debugging information.
     * -d key[=value]            Sets a php.ini value.
     *
     * @return array
     */
    public function getArgs()
    {
        if ($this->options->showVersion) {
            return ['verbose' => ''];
        }

        return [
            'jdepend-xml'      => $this->options->logFile('pdepend-jdepend.xml'),
            'summary-xml'      => $this->options->logFile('pdepend-summary.xml'),
            'jdepend-chart'    => $this->options->logFile('pdepend-jdepend.svg'),
            'overview-pyramid' => $this->options->logFile('pdepend-pyramid.svg'),
            $this->options->ignore->pdepend(),
            $this->options->srcDir
        ];
    }

    protected function getBinary()
    {
        return PROJECT_ROOT . '/vendor/bin/pdepend';
    }
}
