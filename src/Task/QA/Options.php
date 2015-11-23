<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA;

use GreenCape\Robo\Configuration;

/**
 * Class Options
 *
 * @package GreenCape\Robo\Task\QA
 */
class Options
{
    /** @var array A list of files and directories to work on */
    public $source;
    /** @var string The directory, where log output is stored */
    public $logDir;
    /** @var array A list of files and directories to skip */
    public $ignore;
    /** @var array A list of valid source code filename extensions */
    public $suffices;
    /** @var  string The directory, where config files can be found */
    public $configDir;

    /** @var boolean */
    public $isSavedToFiles;
    /** @var boolean */
    public $isOutputPrinted;
    /** @var boolean */
    public $isQuiet;
    /** @var boolean */
    public $isVerbose;

    /**
     * Options constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $options               = $this->completeOptions($options);
        $this->source          = preg_split('~,\s*~', $options['source']);
        $this->logDir          = $options['logDir'];
        $this->ignore          = preg_split('~,\s*~', $options['ignore']);
        $this->suffices        = preg_split('~,\s*~', $options['suffix']);
        $this->configDir       = $options['configDir'];
        $this->isSavedToFiles  = $options['output'] == 'file';
        $this->isOutputPrinted = $this->isSavedToFiles ? $options['verbose'] : true;
        $this->isQuiet         = $options['quiet'];
        $this->isVerbose       = $options['verbose'] & !$options['quiet'];
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function toFile($file)
    {
        return "{$this->logDir}/{$file}";
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function completeOptions(array $options)
    {
        if (is_null($options['source'])) {
            $options['source'] = Configuration::get('project.source');
        }
        if (is_null($options['logDir'])) {
            $options['logDir'] = Configuration::get('project.log.dir');
        }
        if (is_null($options['ignore'])) {
            $options['ignore'] = Configuration::get('project.ignore');
        }
        if (is_null($options['suffix'])) {
            $options['suffix'] = Configuration::get('project.suffices');
        }
        if (is_null($options['configDir'])) {
            $options['configDir'] = Configuration::get('project.config.dir');
        }
        if (is_null($options['output'])) {
            $options['output'] = 'cli';

            return $options;
        }

        return $options;
    }
}
