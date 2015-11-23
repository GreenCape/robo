<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\CodeSniffer;

use Robo\Result;

/**
 * CodeSniffer Document
 *
 * ``` php
 * <?php
 * // Simple execution
 * $this->taskCodeSnifferDocument()->run();
 *
 * // Create documentation for the PEAR standard
 * $this->taskCodeSnifferDocument()
 *      ->standard('PEAR')
 *      ->run();
 *
 * // Write documentation for the default (configured) standard to a file
 * $this->taskCodeSnifferDocument()
 *      ->outfile('docs/codestyle.html)
 *      ->run();
 *
 * // Write documentation for the default (configured) standard to a file using a custom template
 * $this->taskCodeSnifferDocument()
 *      ->template('tmpl/codestyle.html')
 *      ->outfile('docs/codestyle.html')
 *      ->run();
 * ?>
 * ```
 */
class Document extends Base
{
    /** @var string  */
    protected $template;

    /** @var string */
    protected $outfile;

    /**
     * @return Result
     */
    public function run()
    {
        $template = str_replace(PROJECT_ROOT . '/', '', $this->template);
        $standard = preg_replace('~^.*?,\s*~', '', $this->standard);
        $this->printTaskInfo("Generating documentation for <info>{$standard}</info> using <info>{$template}</info> template");

        $this->startTimer();
        (new DocGenerator($standard))
            ->setTemplate($this->template)
            ->setOutfile($this->outfile)
            ->generate();
        $this->stopTimer();

        return new Result($this, 0, null, ['time' => $this->getExecutionTime()]);
    }

    /**
     * @param $template
     *
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param $outfile
     *
     * @return $this
     */
    public function outfile($outfile)
    {
        $this->outfile = $outfile;
        return $this;
    }
}
