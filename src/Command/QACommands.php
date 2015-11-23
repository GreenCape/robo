<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Command;

use GreenCape\Robo\Common\OutputControl;
use GreenCape\Robo\Task\QA\Options;

/**
 * Trait QACommands
 *
 * @package GreenCape\Robo\Command
 */
trait QACommands
{
    use OutputControl;
    use \GreenCape\Robo\Task\QA\loadTasks;

    /**
     * List the available QA tools and their version
     */
    public function qaTools()
    {
        $this->taskQAToolVersions()->run();
    }

    /**
     * Run all available QA tools
     *
     * For documentation of PHP MessDetector (PHPMD) @see http://phpmd.org/
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config files are stored
     */
    public function qa(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => 'file',
            'configDir' => null,
        ]
    ) {
        $this->taskQAAllTools()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the mess detector
     *
     * For documentation of PHP MessDetector (PHPMD) @see http://phpmd.org/
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaMess(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQAMessDetector()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the dependency analyser
     *
     * For documentation of PHP Depend (PDepend) @see http://pdepend.org/documentation/getting-started.html
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaDepend(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQADepend()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the copy paste analyser
     *
     * For documentation of PHP Copy/Paste Detector (PHPCPD) @see https://github.com/sebastianbergmann/phpcpd
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaDuplicate(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQACopyPaste()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the code sniffer
     *
     * For documentation of PHP CodeSniffer @see https://www.squizlabs.com/php-codesniffer/
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaCodestyle(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQACodeSniffer()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the counter
     *
     * For documentation of PHPLOC @see https://github.com/sebastianbergmann/phploc
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaLoc(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQAQuantity()
             ->options(new Options($options))
             ->run();
    }

    /**
     * Run the metrics analyser
     *
     * For documentation of PHP Metrics @see http://www.phpmetrics.org/
     *
     * @param array $options
     * @option $source A comma separated list of files and directories to work on
     * @option $suffix A comma separated list of valid source code filename extensions
     * @option $logDir The directory, where log output is stored
     * @option $ignore A comma separated list of files and directories to skip
     * @option $output The output, i.e., one of 'file' or 'cli'
     * @option $configDir The directory, where the config file phpmd.xml is stored
     */
    public function qaMetrics(
        $options = [
            'source'    => null,
            'suffix'    => null,
            'logDir'    => null,
            'ignore'    => null,
            'output'    => null,
            'configDir' => null,
        ]
    ) {
        $this->taskQAMetrics()
             ->options(new Options($options))
             ->run();
    }
}
