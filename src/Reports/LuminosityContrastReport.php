<?php
/**
 * @file
 * Provides the ReportComponent interface.

 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

use OttawaDeveloper\Tools\ColorAnalyzer\ColorContext;

/**
 * Displays the luminosity contrast ratio for the color pairing.
 * 
 * @package color_reporting
 */
class LuminosityContrastReport implements ReportComponent {

  /**
   * @var string
   */
  private $columnName;
  
  /**
   * Number of decimal places to show.
   * 
   * @var int
   */
  private $decimals;

  /**
   * @param string $name
   * @param int $decimals
   *   The number of decimal places to show.
   */
  public function __construct($name, $decimals = 2) {
    $this->columnName = $name;
    $this->decimals = $decimals;
  }
  
  public function columnHeader() {
    return $this->columnName;
  }
  
  public function columnValue(ColorContext $color) {
    return number_format(round($color->diffLuminosity(), $this->decimals), $this->decimals);
  }
  
}
