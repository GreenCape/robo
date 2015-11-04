<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics;

class Options
{
    /** @var string The source code directory */
    public $srcDir;
    /** @var string The configuration directory */
    public $cfgDir;
    /** @var string The output directory for log files */
    public $logDir;
    /** @var IgnoredPaths */
    public $ignore;
    /** @var bool */
    public $showVersion = false;
    /** @var boolean */
    public $isSavedToFiles;
    /** @var boolean */
    public $isOutputPrinted;
    /** @var array */
    private $enabledTools;
    private $defaultOptions = [
        'srcDir'       => '.',
        'cfgDir'       => 'build/config',
        'logDir'       => 'build/logs',
        'ignoredDirs'  => 'vendor',
        'ignoredFiles' => '',
        'tools'        => 'phploc,phpcpd,phpcs,pdepend,phpmd,phpmetrics',
        'output'       => 'file',
        'verbose'      => true,
        'version'      => false
    ];

    public function __construct(array $options)
    {
        $options = array_merge($this->defaultOptions, $options);

        $this->srcDir          = $this->escape($options['srcDir']);
        $this->cfgDir          = $options['cfgDir'];
        $this->logDir          = $options['logDir'];
        $this->ignore          = new IgnoredPaths($options['ignoredDirs'], $options['ignoredFiles']);
        $this->isSavedToFiles  = $options['output'] == 'file';
        $this->isOutputPrinted = $this->isSavedToFiles ? $options['verbose'] : true;
        $tools                 = $this->isSavedToFiles ? $options['tools']
            : str_replace('pdepend', '', $options['tools']);
        $this->enabledTools    = explode(',', $tools);
        $this->showVersion     = $options['version'];
    }

    public function escape($file)
    {
        return "\"{$file}\"";
    }

    public function isEnabled($tool)
    {
        return in_array($tool, $this->enabledTools);
    }

    public function logFile($file)
    {
        return $this->escape("{$this->logDir}/{$file}");
    }

    public function cfgFile($file)
    {
        return $this->escape("{$this->cfgDir}/{$file}");
    }
}
