<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class DependencyDetectorCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PDepend 2 is used as Dependency Detector');
        $I->runShellCommand('robo metrics:version', false);
        $I->seeShellOutputMatches('~PDepend 2\.\d+\.\d+~i');
    }
}
