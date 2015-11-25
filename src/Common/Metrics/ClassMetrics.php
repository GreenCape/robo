<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Common\Metrics;

class ClassMetrics extends AbstractMetrics
{
    protected $type = '';

    public function __construct($name, $type, $data = [])
    {
        parent::__construct($name, $data);
        $this->type = $type;
    }
}
