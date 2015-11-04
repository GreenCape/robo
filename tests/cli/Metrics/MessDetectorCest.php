<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class MessDetectorCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPMD 2 is used as Mess Detector');
        $I->runShellCommand('robo metrics:version', false);
        $I->seeShellOutputMatches('~phpmd 2\.\d+\.\d+~i');
    }
}
