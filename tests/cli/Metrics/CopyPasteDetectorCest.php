<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class CopyPasteDetectorCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPCPD 2 is used as Copy & Paste Detector');
        $I->runShellCommand('robo metrics:version', false);
        $I->seeShellOutputMatches('~phpcpd 2\.\d+\.\d+~i');
    }
}
