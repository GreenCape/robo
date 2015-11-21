<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA;

use GreenCape\Robo\Task\QA\Tool\PDepend;
use GreenCape\Robo\Task\QA\Tool\PhpCpd;
use GreenCape\Robo\Task\QA\Tool\PhpCs;
use GreenCape\Robo\Task\QA\Tool\PhpLoc;
use GreenCape\Robo\Task\QA\Tool\PhpMd;
use GreenCape\Robo\Task\QA\Tool\PhpMetrics;

/**
 * Trait loadTasks
 *
 * @package GreenCape\Robo\Task\QA
 */
trait loadTasks
{
    /**
     * @return ToolVersions
     */
    protected function taskQAToolVersions()
    {
        return new ToolVersions();
    }

    /**
     * @return AllTools
     */
    protected function taskQAAllTools()
    {
        return new AllTools();
    }

    /**
     * @return PhpMd
     */
    protected function taskQAMessDetector()
    {
        return new PhpMd();
    }

    /**
     * @return PDepend
     */
    protected function taskQADepend()
    {
        return new PDepend();
    }

    /**
     * @return PhpCpd
     */
    protected function taskQACopyPaste()
    {
        return new PhpCpd();
    }

    /**
     * @return PhpCs
     */
    protected function taskQACodeSniffer()
    {
        return new PhpCs();
    }

    /**
     * @return PhpLoc
     */
    protected function taskQAQuantity()
    {
        return new PhpLoc();
    }

    /**
     * @return PhpMetrics
     */
    protected function taskQAMetrics()
    {
        return new PhpMetrics();
    }
}
