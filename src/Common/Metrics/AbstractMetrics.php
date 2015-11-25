<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Common\Metrics;

abstract class AbstractMetrics
{
    protected $name = '';
    protected $data = [];
    /** @var AbstractMetrics[] */
    protected $children = [];

    public function __construct($name, $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param        $key
     * @param string $idx
     *
     * @return number
     * @throws \Exception
     */
    public function get($key, $idx = 'avg')
    {
        if (!isset($this->data[$key])) {
            $aggregate = $this->getAggregate($key);

            return $aggregate[$idx];
        }

        return $this->data[$key];
    }

    public function getAggregate($key)
    {
        if (empty($this->children)) {
            if (!isset($this->data[$key])) {
                return [
                    'sum' => 0,
                    'avg' => 0,
                    'cnt' => 0,
                    'ord' => [],
                    'min' => 0,
                    'med' => 0,
                    'max' => 0,
                ];
            }

            return [
                'sum' => $this->data[$key],
                'avg' => $this->data[$key],
                'cnt' => 1,
                'ord' => [$this->data[$key]],
                'min' => $this->data[$key],
                'med' => $this->data[$key],
                'max' => $this->data[$key],
            ];
        }

        $sum = $cnt = 0;
        $ord = [];
        foreach ($this->children as $child) {
            $values = $child->getAggregate($key);
            $sum += $values['sum'];
            $cnt += $values['cnt'];
            $ord = array_merge($ord, $values['ord']);
        }
        sort($ord);
        $idx = (count($ord) - 1) / 2;
        $med = $idx == intval($idx) ? $ord[intval($idx)] : ($ord[intval($idx)] + $ord[intval($idx + 1)]) / 2;

        return [
            'sum' => $sum,
            'avg' => $sum / $cnt,
            'cnt' => $cnt,
            'ord' => $ord,
            'min' => reset($ord),
            'med' => $med,
            'max' => end($ord),
        ];
    }

    public function addChild(AbstractMetrics $child)
    {
        $this->children[(string)$child] = $child;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function cyclomaticComplexity()
    {
        return $this->get('ccn', 'sum');
    }

    public function maintainabilityIndex()
    {
        return $this->maintainabilityIndexWithoutComments() + $this->maintainabilityIndexCommentWeight();
    }

    public function maintainabilityIndexWithoutComments()
    {
        $loc = $this->get('loc');
        $nom = $this->get('nom');

        if ($nom == 0) {
            return null;
        }

        return 171 - 5.2 * log($this->get('hv')) - .23 * $this->get('ccn') - 16.2 * log($loc / $nom);
    }

    public function maintainabilityIndexCommentWeight()
    {
        $loc  = $this->get('loc');
        $cloc = $this->get('cloc');

        if ($loc == 0) {
            return null;
        }

        return 50 * sin(sqrt(2.4 * $cloc / $loc));
    }
}
