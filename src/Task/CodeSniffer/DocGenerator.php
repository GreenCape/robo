<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\CodeSniffer;

/**
 * Class DocGenerator
 *
 * @package GreenCape\Robo\Task\CodeSniffer
 */
class DocGenerator extends \PHP_CodeSniffer_DocGenerators_Generator
{
    /** @var string */
    private $template = null;

    /** @var string */
    private $outFile = null;

    /**
     * Set the template path
     *
     * @param string $template
     *        The path to the template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set the path of the output file.
     *
     * @param string $filename
     *        The path to the output file
     *
     * @return $this
     */
    public function setOutfile($filename)
    {
        $this->outFile = $filename;

        return $this;
    }

    /**
     * Generate the documentation for a standard.
     *
     * This method generates a documentation file based on the XML files in the Docs directory of a standard.
     * This information is stored in XML files having the following format:
     *
     * ```xml
     *  <documentation title="Title of the Sniff">
     *      <standard>
     *          <![CDATA[
     *          Description of the rule/s, that is/are checked by this sniff.
     *          ]]>
     *      </standard>
     *      <code_comparison>
     *          <code title="Valid: Description of why this is right.">
     *              <![CDATA[
     *              Sample code showing how it is done right. The significant locations are highlighted using <em></em>.
     *              ]]>
     *          </code>
     *          <code title="Invalid: Description of why this is wrong.">
     *              <![CDATA[
     *              Sample code showing a wrong way. The significant locations are highlighted using <em></em>.
     *              ]]>
     *          </code>
     *      </code_comparison>
     *  </documentation>
     * ```
     *
     * `standard` and `code_comparision` blocks can occur in any order and number.
     * A `code_comparision` block contains exactly two code blocks, the first one always showing the *valid* code example.
     *
     * @return void
     * @see    processSniff()
     */
    public function generate()
    {
        $data = [
            'standard' => preg_replace('~^.*/([^/]+)$~', '\1', $this->getStandard()),
            'rules'    => [],
            'version'  => \PHP_CodeSniffer::VERSION
        ];

        foreach ($this->getStandardFiles() as $standardFile) {
            $this->processDocFile($data, $standardFile);
        }
        ksort($data['rules']);

        $this->render($data, $this->outFile, $this->getTemplate());
    }

    /**
     * Process the documentation for a single sniff.
     *
     * @param \DOMNode $doc
     *        The DOMNode object for the sniff.
     *        It represents the "documentation" tag in the XML standard file.
     *
     * @return string
     */
    protected function processSniff(\DOMNode $doc)
    {
        $sniff = [
            'title'  => $this->getTitle($doc),
            'id'     => str_replace(' ', '-', strtolower($this->getTitle($doc))),
            'blocks' => []
        ];

        foreach ($doc->childNodes as $node) {
            if ($node->nodeName === 'standard') {
                $sniff['blocks'][] = $this->processTextBlock($node);
            } elseif ($node->nodeName === 'code_comparison') {
                $sniff['blocks'][] = $this->processCodeComparisonBlock($node);
            }
        }

        return $sniff;
    }

    /**
     * Print a text block found in a standard.
     *
     * @param \DOMNode $node
     *        The DOMNode object for the text block.
     *
     * @return string
     */
    private function processTextBlock($node)
    {
        $replacements = [
            '&lt;em&gt;'  => '<em>',
            '&lt;/em&gt;' => '</em>'
        ];

        return [
            'type'    => 'text',
            'content' => $this->replace(htmlspecialchars(trim($node->nodeValue)), $replacements)
        ];
    }

    /**
     * Print a code comparison block found in a standard.
     *
     * @param \DOMElement $node
     *        The DOMElement object for the code comparison block.
     *
     * @return string
     */
    private function processCodeComparisonBlock($node)
    {
        $replacements = [
            '<?php' => '&lt;?php',
            "\n"    => '<br/>',
            ' '     => '&nbsp;',
        ];

        $codeBlocks = $node->getElementsByTagName('code');

        return [
            'type'    => 'code',
            'content' => [
                'valid'   => [
                    'title' => $this->getTitle($codeBlocks->item(0)),
                    'code'  => $this->replace(trim($codeBlocks->item(0)->nodeValue), $replacements)
                ],
                'invalid' => [
                    'title' => $this->getTitle($codeBlocks->item(1)),
                    'code'  => $this->replace(trim($codeBlocks->item(1)->nodeValue), $replacements)
                ]
            ]
        ];
    }

    /**
     * Convenience method for bulk replacement
     *
     * @param  string $string
     * @param  array  $replacements search => replace
     *
     * @return string
     */
    private function replace($string, $replacements)
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $string
        );
    }

    /**
     * Render the data structure
     *
     * @param array  $data
     *        The data structure generated from the doc files
     * @param string $outfile
     *        The output file. If omitted (== null), the output is sent to STDOUT,
     * @param        $template
     *        The path to the Twig template to use for output
     */
    private function render($data, $outfile, $template)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(dirname($template)));

        if (is_null($outfile)) {
            echo $twig->render(basename($template), $data);
        } else {
            file_put_contents($outfile, $twig->render(basename($template), $data));
        }
    }

    /**
     * Get the template.
     *
     * If not set, the default template is returned.
     *
     * @return string
     */
    private function getTemplate()
    {
        if (empty($this->template)) {
            $this->template = __DIR__ . '/codestyle.html';
        }

        return $this->template;
    }

    /**
     * Parse a doc file.
     *
     * @param array $data
     *        The data structure
     * @param       $standardFile
     *        The path of the doc file
     */
    private function processDocFile(&$data, $standardFile)
    {
        $doc   = new \DOMDocument;
        $doc->load($standardFile);
        preg_match('~/([^/]+)/Docs/([^/]+)/([^/]+)Standard.xml$~', $standardFile, $match);

        $this->addGroup($data, $match[2]);
        $this->addSniff(
            $data,
            $this->processSniff($doc->getElementsByTagName('documentation')->item(0)),
            $match[2],
            "{$match[1]}.{$match[2]}.{$match[3]}"
        );
    }

    /**
     * Add a group to the data structure
     *
     * @param array  $data
     *        The data structure
     * @param string $group
     *        The name of the group
     */
    private function addGroup(&$data, $group)
    {
        if (!isset($data['rules'][$group])) {
            $data['rules'][$group] = [
                'name'   => $group,
                'sniffs' => []
            ];
        }
    }

    /**
     * Add a sniff to the data structure
     *
     * @param array $data
     *        The data structure
     * @param $sniff
     *        The sniff structure
     * @param $group
     *        The group name
     * @param $name
     *        The sniff name
     */
    private function addSniff(&$data, $sniff, $group, $name)
    {
        $sniff['name']                     = $name;
        $data['rules'][$group]['sniffs'][] = $sniff;
    }
}
