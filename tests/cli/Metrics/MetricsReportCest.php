<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class MetricsReportCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPMetrics 1 is used as Metrics Report generator');
        $I->runShellCommand('robo metrics:version', false);
        $I->seeShellOutputMatches('~phpmetrics.* 1\.\d+\.\d+~i');
    }
}
