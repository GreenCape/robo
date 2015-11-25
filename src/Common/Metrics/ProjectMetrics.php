<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Common\Metrics;

class ProjectMetrics extends AbstractMetrics
{
    public function __construct($name, $xmlFile)
    {
        $doc = new \DOMDocument();
        $doc->load($xmlFile);
        /** @var \DOMElement $root */
        $root = $doc->childNodes->item(0);

        parent::__construct($name, [
            'ahh'    => $root->getAttribute('ahh'),
            'andc'   => $root->getAttribute('andc'),
            'calls'  => $root->getAttribute('calls'),
            'ccn'    => $root->getAttribute('ccn'),
            'ccn2'   => $root->getAttribute('ccn2'),
            'cloc'   => $root->getAttribute('cloc'),
            'clsa'   => $root->getAttribute('clsa'),
            'clsc'   => $root->getAttribute('clsc'),
            'eloc'   => $root->getAttribute('eloc'),
            'fanout' => $root->getAttribute('fanout'),
            'leafs'  => $root->getAttribute('leafs'),
            'lloc'   => $root->getAttribute('lloc'),
            'loc'    => $root->getAttribute('loc'),
            'maxDIT' => $root->getAttribute('maxDIT'),
            'ncloc'  => $root->getAttribute('ncloc'),
            'roots'  => $root->getAttribute('roots'),
        ]);
        foreach ($root->childNodes as $node) {
            if ($node->nodeName == 'package') {
                $this->addChild($this->parsePackage($node));
            }
        }
    }

    /**
     * @param \DOMElement $node
     *
     * @return PackageMetrics
     */
    private function parsePackage(\DOMElement $node)
    {
        $package = new PackageMetrics($node->getAttribute('name'), [
            'nop' => 1,
        ]);
        foreach ($node->childNodes as $child) {
            if (in_array($child->nodeName, ['class', 'interface', 'trait'])) {
                $package->addChild($this->parseClass($child));
            } elseif ($child->nodeName == 'function') {
                $package->addChild($this->parseMethod($child));
            }
        }

        return $package;
    }

    /**
     * @param \DOMElement $node
     *
     * @return ClassMetrics
     */
    private function parseClass(\DOMElement $node)
    {
        $type  = $node->nodeName;
        $class = new ClassMetrics($node->getAttribute('name'), $type, [
            'ca'     => $node->getAttribute('ca'),
            'cbo'    => $node->getAttribute('cbo'),
            'ce'     => $node->getAttribute('ce'),
            'impl'   => $node->getAttribute('impl'),
            'cis'    => $node->getAttribute('cis'),
            'csz'    => $node->getAttribute('csz'),
            'npm'    => $node->getAttribute('npm'),
            'vars'   => $node->getAttribute('vars'),
            'varsi'  => $node->getAttribute('varsi'),
            'varsnp' => $node->getAttribute('varsnp'),
            'wmc'    => $node->getAttribute('wmc'),
            'wmci'   => $node->getAttribute('wmci'),
            'wmcnp'  => $node->getAttribute('wmcnp'),
            'noc'    => $type == 'class' ? 1 : 0,
            'noi'    => $type == 'interface' ? 1 : 0,
            'not'    => $type == 'trait' ? 1 : 0,
            'nof'    => $type == 'function' ? 1 : 0,
        ]);
        foreach ($node->childNodes as $child) {
            if ($child->nodeName == 'method') {
                $class->addChild($this->parseMethod($child));
            }
        }

        return $class;
    }

    /**
     * @param \DOMElement $node
     *
     * @return MethodMetrics
     */
    private function parseMethod(\DOMElement $node)
    {
        return new MethodMetrics($node->getAttribute('name'), [
            'ccn'   => $node->getAttribute('ccn'),
            'ccn2'  => $node->getAttribute('ccn2'),
            'loc'   => $node->getAttribute('loc'),
            'cloc'  => $node->getAttribute('cloc'),
            'eloc'  => $node->getAttribute('eloc'),
            'lloc'  => $node->getAttribute('lloc'),
            'ncloc' => $node->getAttribute('ncloc'),
            'npath' => $node->getAttribute('npath'),
            'hnt'   => $node->getAttribute('hnt'),
            'hnd'   => $node->getAttribute('hnd'),
            'hv'    => $node->getAttribute('hv'),
            'hd'    => $node->getAttribute('hd'),
            'hl'    => $node->getAttribute('hl'),
            'he'    => $node->getAttribute('he'),
            'ht'    => $node->getAttribute('ht'),
            'hb'    => $node->getAttribute('hb'),
            'hi'    => $node->getAttribute('hi'),
            'mi'    => $node->getAttribute('mi'),
            'nom'   => 1,
        ]);
    }
}
