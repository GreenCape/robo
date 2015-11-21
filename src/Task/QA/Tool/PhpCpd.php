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

/**
 * Class PhpCpd
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpCpd extends ShellCommand
{
    /**
     * PhpCpd constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/phpcpd', ' ', '--version');
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

        $this->resetArgs();
        if (!empty($options->suffices)) {
            $this->option('names', '"*' . implode(',*', $options->suffices) . '"');
        }
        if (!empty($options->ignore)) {
            $files = [];
            foreach ($options->ignore as $entry) {
                if (is_dir($entry)) {
                    $this->option('exclude', $entry);
                } else {
                    $files[] = $entry;
                }
            }
            if (!empty($files)) {
                $this->option('names-exclude', implode(',', $files));
            }
        }
        if ($options->isSavedToFiles) {
            $this->option('log-pmd', $options->logDir . '/phpcpd.xml');
        }

        $this->arg(implode(',', $options->source));

        return $this;
    }
}
