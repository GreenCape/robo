<?php
namespace GreenCape\Robo\Task\CodeSniffer;

use GreenCape\Robo\Configuration;
use Robo\Common\Timer;
use Robo\Result;
use Robo\Task\BaseTask;

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
class Document extends BaseTask
{
    protected $standard;
    protected $template;
    protected $outfile;

    use Timer;

    /**
     * Document constructor.
     *
     * Preset options with config values.
     */
    public function __construct()
    {
        $this->standard = Configuration::get('codestyle.standard', 'PSR2');
        $this->template = Configuration::get('codestyle.doc.template', 'build/templates/codestyle.html');
        $this->outfile  = Configuration::get('codestyle.doc.file', null);
    }

    public function run()
    {
        $this->printTaskInfo("Generating documentation for {$this->standard} using {$this->template} template");

        $this->startTimer();
        (new DocGenerator($this->standard))
            ->setTemplate($this->template)
            ->setOutfile($this->outfile)
            ->generate();
        $this->stopTimer();

        return new Result($this, 0, null, ['time' => $this->getExecutionTime()]);
    }

    public function standard($standard)
    {
        if (!empty($standard)) {
            $this->standard = $standard;
        }
        return $this;
    }

    public function template($template)
    {
        if (!empty($template)) {
            $this->template = $template;
        }

        return $this;
    }

    public function outfile($outfile)
    {
        if (!empty($outfile)) {
            $this->outfile = $outfile;
        }

        return $this;
    }
}
