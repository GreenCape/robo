<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class LineCounterCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPLoc 2 is used as Line Counter');
        $I->runShellCommand('robo metrics:version', false);
        $I->seeShellOutputMatches('~phploc 2\.\d+\.\d+~i');
    }
}
