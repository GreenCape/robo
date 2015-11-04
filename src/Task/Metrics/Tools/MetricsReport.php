<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

class MetricsReport extends AbstractTool
{
    /**
     * phpmetrics usage:
     *
     * metrics [options] [--] [<path>]
     *
     * path:
     *     Path to explore
     * options:
     *        --report-html=REPORT-HTML              Path to save report in HTML format. Example: /tmp/report.html
     *        --report-xml=REPORT-XML                Path to save summary report in XML format. Example: /tmp/report.xml
     *        --report-cli                           Enable report in terminal
     *        --violations-xml=VIOLATIONS-XML        Path to save violations in XML format. Example: /tmp/report.xml
     *        --report-csv=REPORT-CSV                Path to save summary report in CSV format. Example: /tmp/report.csv
     *        --report-json=REPORT-JSON              Path to save detailed report in JSON format. Example: /tmp/report.json
     *        --chart-bubbles=CHART-BUBBLES          Path to save Bubbles chart, in SVG format. Example: /tmp/chart.svg. Graphviz **IS** required
     *        --level=LEVEL                          Depth of summary report [default: 0]
     *        --extensions=EXTENSIONS                Regex of extensions to include
     *        --excluded-dirs=EXCLUDED-DIRS          Regex of subdirectories to exclude
     *        --symlinks                             Enable following symlinks
     *        --without-oop                          If provided, tool will not extract any information about OOP model (faster)
     *        --failure-condition=FAILURE-CONDITION  Optional failure condition, in english. For example: average.maintainabilityIndex < 50 or sum.loc > 10000
     *        --config=CONFIG                        Config file (YAML)
     *        --template-title=TEMPLATE-TITLE        Title for the HTML summary report
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
            'extensions' => 'php',
            $this->options->ignore->phpmetrics()
        ];
        if ($this->options->isSavedToFiles) {
            $args['report-html'] = $this->options->logFile('phpmetrics.html');
        } else {
            $args['report-cli'] = '';
        }

        return $args;
    }

    protected function getOptionSeparator()
    {
        return ' ';
    }

    protected function getBinary()
    {
        return PROJECT_ROOT . '/vendor/bin/metrics';
    }
}
