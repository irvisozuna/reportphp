<?php
require __DIR__ . '/../../vendor/autoload.php';

date_default_timezone_set('Asia/Tokyo');

use Thinreports\Report;

$report = new Report(__DIR__ . '/hello_world.tlf');

$page = $report->addPage();
$page->item('world')->setValue('World');
$page->item('sekai')->setValue('世界');

$page = $report->addPage();
$page('world')->setValue('Irvis Ozuna');
$page('sekai')->setValue('世界');

$page = $report->addPage();
$page->setItemValue('world', 'World');
$page->setItemValue('sekai', 'QUe ondas');

$page = $report->addPage();
$page->setItemValues(array(
    'world' => 'World',
    'sekai' => '世界'
));

$report->addPage()->setItemValues(array(
    'world' => 'World',
    'sekai' => '世界'
));



$report->generate(__DIR__ . '/hello_world.pdf');

