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
    /**
     * @param \CliTester $I
     */
    public function testVersion(\CliTester $I)
    {
        $I->wantTo('verify that PHPCS 2 is used as Code Sniffer');
        $I->runShellCommand('vendor/bin/phpcs --version');
        $I->seeShellOutputMatches('~PHP_CodeSniffer version 2\.\d+\.\d+~i');
    }

    /**
     * @param \CliTester            $I
     * @param \Codeception\Scenario $scenario
     */
    public function testDocumentValidHtml(\CliTester $I, \Codeception\Scenario $scenario)
    {
        $I->wantTo('verify that the default template produces valid HTML');

        if (!class_exists('Tidy')) {
            $scenario->skip('Tidy is not available. See http://php.net/manual/en/tidy.installation.php');
        }

        $template = dirname(dirname(__DIR__)) . '/src/Task/CodeSniffer/codestyle.html';
        $outfile = dirname(__DIR__) . '/_output/codestyle.html';

        if (file_exists($outfile)) {
            unlink($outfile);
        }

        $I->dontSeeFileFound($outfile);
        $I->runShellCommand('vendor/bin/robo document:codestyle --outfile ' . $outfile . ' --template ' . $template);
        $I->seeFileFound($outfile);

        $tidy = new \Tidy();
        $tidy->parseFile($outfile);

        $I->assertEquals(0, $tidy->getStatus());

        unlink($outfile);
    }

    /**
     * @param \CliTester            $I
     */
    public function testDocumentUsesRightStandard(\CliTester $I)
    {
        $I->wantTo('verify that the documentor uses the right standard');

        $template = dirname(dirname(__DIR__)) . '/src/Task/CodeSniffer/codestyle.html';
        $outfile  = dirname(__DIR__) . '/_output/codestyle.html';

        if (file_exists($outfile)) {
            unlink($outfile);
        }

        foreach (['Joomla', 'WordPress', 'PSR2'] as $standard) {
            $I->dontSeeFileFound($outfile);
            $I->runShellCommand("vendor/bin/robo document:codestyle --standard $standard --outfile  $outfile --template $template");
            $I->seeFileFound($outfile);

            $I->seeInThisFile("$standard Coding Standards");
            unlink($outfile);
        }
    }
}
