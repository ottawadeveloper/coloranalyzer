<?php
/**
 * @file
 * Provides the ReportRunner class.
 * 
 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

use \OttawaDeveloper\Tools\ColorAnalyzer\ColorPalette;

/**
 * Runs reports, by combining ReportComponent objects with a ColorPalette.
 * 
 * @package color_reporting
 */
class ReportRunner {
  
  /**
   * An array of ReportComponent objects.
   * 
   * @var array
   */
  private $reportComponents = array();
  
  /**
   * @var ReportFormatter
   */
  private $formatter;
  
  /**
   * @param ReportFormatter $formatter
   */
  public function __construct(ReportFormatter $formatter) {
    $this->formatter = $formatter;
  }
  
  /**
   * Adds a report component to the report. Each report component will be run
   * to generate a header value, and then a cell value for each ColorPair.
   * 
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Reports\ReportComponent $component
   */
  public function addReportComponent(ReportComponent $component) {
    $this->reportComponents[] = $component;
  }
  
  /**
   * Runs the report against the given ColorPalette.
   * 
   * @param ColorPalette $palette
   */
  public function runReport(ColorPalette $palette) {
    $this->formatter->start();
    $header = array();
    foreach ($this->reportComponents as $key => $component) {
      $header[$key] = $component->columnHeader();
    }
    $this->formatter->formatHeader($header);
    foreach ($palette as $colorPair) {
      $row = array();
      foreach ($this->reportComponents as $key => $component) {
        $row[$key] = $component->columnValue($colorPair);
      }
      $this->formatter->formatRow($row);
    }
    $this->formatter->end();
  }
  
}
