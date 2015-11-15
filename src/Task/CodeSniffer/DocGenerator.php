<?php
namespace GreenCape\Robo\Task\CodeSniffer;

class DocGenerator extends \PHP_CodeSniffer_DocGenerators_Generator
{
    private $template = null;
    private $outFile = null;

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function setOutfile($filename)
    {
        $this->outFile = $filename;

        return $this;
    }

    /**
     * Generate the documentation for a standard.
     *
     * @return void
     * @see    processSniff()
     */
    public function generate()
    {
        $data = [
            'standard' => preg_replace('~^.*/([^/]+)$~', '\1', $this->getStandard()),
            'rules'   => [],
            'version'  => \PHP_CodeSniffer::VERSION
        ];

        foreach ($this->getStandardFiles() as $standard) {
            $doc = new \DOMDocument;
            $doc->load($standard);
            $documentation = $doc->getElementsByTagName('documentation')->item(0);
            preg_match('~/([^/]+)/Docs/([^/]+)/([^/]+)Standard.xml$~', $standard, $match);
            if (!isset($data['rules'][$match[2]])) {
                $data['rules'][$match[2]] = [
                    'name'  => $match[2],
                    'sniffs' => []
                ];
            }
            $sniff                       = $this->processSniff($documentation);
            $sniff['name']               = "{$match[1]}.{$match[2]}.{$match[3]}";
            $data['rules'][$match[2]]['sniffs'][] = $sniff;
        }
        ksort($data['rules']);

        if (empty($this->template)) {
            $this->template = __DIR__ . '/codestyle.html';
        }

        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(dirname($this->template)));

        if (is_null($this->outFile)) {
            echo $twig->render(basename($this->template), $data);
        } else {
            file_put_contents($this->outFile, $twig->render(basename($this->template), $data));
        }
    }

    /**
     * Process the documentation for a single sniff.
     *
     * @param \DOMNode $doc The DOMNode object for the sniff.
     *                      It represents the "documentation" tag in the XML
     *                      standard file.
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
     * @param \DOMNode $node The DOMNode object for the text block.
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
     * @param \DOMElement $node The DOMElement object for the code comparison block.
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
}
