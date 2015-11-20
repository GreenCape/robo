<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA\Tool;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Task\QA\Options;

class PhpMd extends ShellCommand
{
    /**
     * PhpMd constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/phpmd', ' ', '--version');
    }

    /**
     * @param Options|array $options
     *
     * @return $this
     */
    public function options($options)
    {
        if (!($options instanceof Options)) {
            return parent::options($options);
        }

        $this->resetArgs()
            ->arg(implode(',', $options->source))
            ->arg($options->isSavedToFiles ? 'xml' : 'text')
            ;
        if (file_exists($options->configDir . '/phpmd.xml')) {
            $this->arg($options->configDir . '/phpmd.xml');
        } else {
            $this->arg('cleancode,codesize,controversial,design,naming,unusedcode');
        }
        if ($options->isSavedToFiles) {
            $this->option('reportfile', $options->logDir . '/phpmd.xml');
        }
        if (!empty($options->suffices)) {
            $this->option('suffixes', implode(',', $options->suffices));
        }
        if (!empty($options->ignore)) {
            $this->option('exclude', implode(',', $options->ignore));
        }

        return $this;
    }
}
