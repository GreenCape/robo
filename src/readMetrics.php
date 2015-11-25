<?php
namespace GreenCape\Robo\Common\Metrics;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$project = new ProjectMetrics('Test', 'build/logs/summary.xml');
listMetrics($project);

/**
 * @param AbstractMetrics $root
 * @param int             $level
 */
function listMetrics($root, $level = 0)
{
    $loc  = $root->get('loc', 'sum');
    $len  = 32;
    $name = str_repeat('  ', $level) . $root;
    if (strlen($name) > $len) {
        $name = substr($name, 0, $len - 1) . '…';
    }
    if ($loc == 0) {
        printf("%-{$len}s  MI:    --  CC: --  LoC:% 4d  Time:    --   \n",
            $name,
            $loc
        );

        return;
    }
    printf("%-{$len}s  MI:% 6.0f  CC:% 3d  LoC:% 4d  Time:%10s\n",
        $name,
        $root->maintainabilityIndex(),
        $root->get('ccn', 'max'),
        $loc,
        time($root->get('ht', 'sum'))
    );

    foreach ($root->getChildren() as $child) {
        listMetrics($child, $level + 1);
    }
}

function time($ht, $htFactor = 18)
{
    $factor   = [
        [1 / $htFactor, 's'],
        [60, 'min'],
        [60, 'h'],
        [8, 'd'],
        [5, 'wk'],
        [365.2522 / 7 / 12, 'mon'],
        [12, 'yr'],
        [PHP_INT_MAX, '']
    ];

    $time = $ht;
    foreach ($factor as $i => $info) {
        $time = $time / $info[0];
        if ($time < $factor[$i + 1][0] / 2) {
            return sprintf('%.2f %-3s', round($time * 4) / 4, $info[1]);
        }
    }

    return '';
}






