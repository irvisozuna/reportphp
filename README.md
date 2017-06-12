# Reporteador PHP con Thinreport


## Quick Start

### Step1 Install Thinreports Editor

See the [Official Installation Guide](http://www.thinreports.org/documentation/en/getting-started/installation.html).

### Step2 Install Thinreports Generator for PHP

    $ composer require irvis/qbitreport dev-master

### Step3 Create a report format file using the Editor

Follow ["Step1 Creating the layout for the report"](http://www.thinreports.org/documentation/en/getting-started/quickstart.html#toc-2) section in the official doucmentation.

### Step4 Write code for generating a PDF

```php
<?php
// date_default_timezone_set('Asia/Tokyo');

$report = new Thinreports\Report('hello_world.tlf');

// 1st page
$page = $report->addPage();
$page->item('world')->setValue('World');
$page->item('thinreports')->setValue('Thinreports');

// 2nd page
$page = $report->addPage();
$page('world')->setValue('PHP');
$page('thinreports')->setValue('Thinreports PHP');

// 3rd page
$page = $report->addPage();
$page('world')->setValue('World')
              ->setStyle('color', '#ff0000');
$page('hello')->hide();

// 4th page
$page = $report->addPage();
$page->setItemValue('thinreports', 'PDF');
$page->setItemValues(array(
  'world' => 'PHP'
));

// 5th page
$page = $report->addPage();
$page->item('world_image')->setSource('/path/to/world.png');

// 6th page: Using other .tlf file
$page = $report->addPage('hello_php.tlf')
$page->item('world')->setValue('php');

// 7th page: Insert a blank page
$report->addBlankPage();

$report->generate('hello_world.pdf');

// You can get content of the PDF in the following code:
$pdf_data = $report->generate();
```


## License

Thinreports PHP is licensed under the MIT-License.
See [LICENSE](https://github.com/thinreports-php/thinreports-php/blob/master/LICENSE) for further details.

### Dependency Library & Resource

#### TCPDF

LGPLv3 / Copyright (c) Nicola Asuni [Tecnick.com](http://www.tecnick.com) LTD