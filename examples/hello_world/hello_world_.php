<?php
require __DIR__ . '/../../vendor/autoload.php';

date_default_timezone_set('Asia/Tokyo');

use Thinreports\Report;

$report = new Report(__DIR__ . '/hello_world_.tlf');

//$page = $report->addPage();
//$page->item('world')->setValue('World');
//$page->item('sekai')->setValue('世界');

//$page = $report->addPage();
//$page('world')->setValue('Irvis Ozuna');
//$page('sekai')->setValue('世界');

$page = $report->addPage();
$string = "<p align='justify'>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p> Pellentesque faucibus ligula at orci aliquam, vel convallis nibh eleifend. Fusce finibus ullamcorper leo. Phasellus consequat lacus ut neque vehicula lacinia. Quisque ipsum ipsum, accumsan eu sollicitudin vel, malesuada vitae ex. Morbi mauris lectus, mollis non neque et, rhoncus condimentum neque. Donec molestie augue at nisi semper, at faucibus ante rutrum. Pellentesque et sollicitudin nisi. Sed ut nibh libero. Vivamus accumsan, urna fringilla mollis pellentesque, libero sapien ullamcorper nunc, at blandit dolor leo a augue. Sed commodo lacus vitae massa placerat scelerisque.

Sed bibendum, magna eget imperdiet dignissim, justo arcu accumsan libero, vitae ultrices lectus nunc nec mi. Nam molestie molestie velit ut mattis. Donec posuere, nulla ut cursus vestibulum, enim enim faucibus odio, et ullamcorper justo magna nec sem. Nullam dapibus fermentum massa nec ultricies. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas suscipit dignissim urna, eu vehicula justo pulvinar et. Cras ut eros diam.

Quisque placerat molestie orci. Donec interdum condimentum arcu, in dignissim enim tincidunt nec. Donec dignissim lectus in tellus aliquet, eget interdum est pretium. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed sed semper urna, tristique aliquam tortor. Donec at felis sit amet sapien consequat dapibus. Vivamus sed ultrices nulla. Morbi facilisis felis elit.

Nulla ac viverra felis. Proin orci lorem, scelerisque at maximus sed, euismod vel dolor. Pellentesque non mi nec lacus iaculis euismod id eu ipsum. Integer sed molestie tortor, sed suscipit justo. Quisque porta varius dui a posuere. In vel dapibus elit. Vivamus vestibulum nisi semper mauris sodales porttitor. Sed convallis non magna eget semper. In id dui sem. Nulla rutrum nec ex non dignissim. Curabitur mollis mi dapibus aliquam convallis. Aliquam vitae semper purus, id dictum turpis. Ut at accumsan est. Donec nec sem eget ligula dignissim elementum.

Integer aliquet, lorem sed elementum elementum, nisi est vestibulum erat, nec congue orci risus at nulla. Praesent in varius turpis. Mauris id nulla nec nibh dignissim finibus. Maecenas eget nibh hendrerit, dignissim neque et, tempus turpis. Morbi a pulvinar libero. Sed dignissim libero augue, non eleifend nisl elementum quis. Fusce eu viverra nunc. Curabitur fermentum fringilla nulla, a pretium arcu bibendum ac. Pellentesque posuere nec felis vitae vehicula. Quisque a lorem eu nulla sodales ultricies quis id ligula. Suspendisse tincidunt elit sapien, non consectetur dolor lacinia ac. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam ac consectetur nisl, id suscipit nunc. Fusce at sodales massa. Vestibulum eu fermentum leo.";
/*$page->setItemValue('asistentes', $string);*/
$page->setItemValue('text', $string);
/*$page = $report->addPage();
$page('world')->setValue('Irvis Ozuna');*/
/*$page = $report->addPage();
$page->setItemValues(array(
    'world' => 'que ondas'
));*/
//$page->setItemValue('sekai', 'QUe ondas');

//$page = $report->addPage();
/*$page->setItemValues(array(
    'world' => 'World',
    'sekai' => '世界'
));

$report->addPage()->setItemValues(array(
    'world' => 'World',
    'sekai' => '世界'
));*/



$report->generate(__DIR__ . '/hello_world.pdf');

