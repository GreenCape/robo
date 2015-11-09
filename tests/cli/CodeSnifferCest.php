<?php
/**
 * @package   GreenCape\RoboTest
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\RoboTest;

class CodeSnifferCest
{
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPCS 2 is used as Code Sniffer');
        $I->runShellCommand('vendor/bin/phpcs --version', false);
        $I->seeShellOutputMatches('~PHP_CodeSniffer version 2\.\d+\.\d+~i');
    }
}
