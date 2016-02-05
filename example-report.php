<?php

  require_once 'vendor/autoload.php';
  
  use \OttawaDeveloper\Tools\ColorAnalyzer\Reports\ColorTextReport;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Reports\CommandLineFormatter;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\HexProcessor;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\HslProcessor;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\ColorKeywordProcessor;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\RgbProcessor;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Reports\Wcag2ComplianceReport;
  use \OttawaDeveloper\Tools\ColorAnalyzer\Reports\ReportRunner;
  use \OttawaDeveloper\Tools\ColorAnalyzer\ColorContext;
  
  $colors = [
    '#FFFFFF',
    '#000000',
    '#FF0000',
    '#00FF00',
    '#0000FF',
    '#FF00FF',
    '#FFFF00',
    '#00FFFF66',
    'rgb(99, 99, 99)',
    'rgba(100, 50, 25, 0.75)',
    'hsl(60, 10, 50)',
    'hsla(90, 50, 10, 0.75)',
    'darkblue',
  ];
  
  $factory = new \OttawaDeveloper\Tools\ColorAnalyzer\ColorFactory();
  $factory->registerProcessor(new ColorKeywordProcessor());
  $factory->registerProcessor(new HexProcessor());
  $factory->registerProcessor(new HslProcessor());
  $factory->registerProcessor(new RgbProcessor());
  
  $palette = new OttawaDeveloper\Tools\ColorAnalyzer\ColorPalette($factory, $colors);
  $palette->sortColorArray(function(ColorContext $a, ColorContext $b) {
    return ($b->diffLuminosity() - $a->diffLuminosity()) * 100;
  });
  
  $invalid = $palette->getInvalidStrings();
  
  if (!empty($invalid)) {
    echo 'The following strings could not be processed:' . PHP_EOL;
    foreach ($invalid as $invalidString) {
      echo '- ' . $invalidString . PHP_EOL;
    }
    echo PHP_EOL;
  }
  
  $report = new ReportRunner(new CommandLineFormatter(array(
    0 => 12,
    1 => 12,
    2 => array(
      'width' => 12,
      'align' => 'right',
    ),
    3 => 12,
    4 => 12,
  )));
  $report->addReportComponent(new ColorTextReport(new HexProcessor(), 'Foreground', ColorTextReport::FOREGROUND));
  $report->addReportComponent(new ColorTextReport(new HexProcessor(), 'Background', ColorTextReport::BACKGROUND));
  $report->addReportComponent(new OttawaDeveloper\Tools\ColorAnalyzer\Reports\LuminosityContrastReport('Contrast'));
  $report->addReportComponent(new Wcag2ComplianceReport('AA', Wcag2ComplianceReport::LEVEL_AA));
  $report->addReportComponent(new Wcag2ComplianceReport('AAA', Wcag2ComplianceReport::LEVEL_AAA));
  
  $report->runReport($palette);