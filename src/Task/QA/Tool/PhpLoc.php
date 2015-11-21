<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA\Tool;

/**
 * Class PhpLoc
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpLoc extends PhpLocCpdBase
{
    protected $logOption;
    protected $logFile;

    /**
     * PhpLoc constructor.
     */
    public function __construct()
    {
        $this->logFile   = 'phploc.xml';
        $this->logOption = 'log-xml';
        parent::__construct('vendor/bin/phploc', ' ', '--version');
    }
}
