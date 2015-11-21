<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA\Tool;

/**
 * Class PhpCpd
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpCpd extends PhpLocCpdBase
{
    protected $logOption;
    protected $logFile;

    /**
     * PhpCpd constructor.
     */
    public function __construct()
    {
        $this->logFile   = 'phpcpd.xml';
        $this->logOption = 'log-pmd';
        parent::__construct('vendor/bin/phpcpd', ' ', '--version');
    }
}
