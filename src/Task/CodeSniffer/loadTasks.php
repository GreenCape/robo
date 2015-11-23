<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\CodeSniffer;

/**
 * Trait loadTasks
 *
 * @package GreenCape\Robo\Task\CodeSniffer
 */
trait loadTasks
{
    /**
     * @return Document
     */
    protected function taskCSDocument()
    {
        return new Document;
    }
}
