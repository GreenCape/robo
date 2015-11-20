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

trait loadTasks
{
    protected function taskQAToolVersions()
    {
        return new ToolVersions();
    }

    protected function taskQAAllTools()
    {
        return new AllTools();
    }

    protected function taskQAMessDetector()
    {
        return new PhpMd();
    }

    protected function taskQADepend()
    {
        return new PDepend();
    }

    protected function taskQACopyPaste()
    {
        return new PhpCpd();
    }

    protected function taskQACodeSniffer()
    {
        return new PhpCs();
    }

    protected function taskQAQuantity()
    {
        return new PhpLoc();
    }

    protected function taskQAMetrics()
    {
        return new PhpMetrics();
    }
}
