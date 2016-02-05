<?php
/**
 * @file
 * Provides the Wcag2ComplianceReport.
 * 
 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

use OttawaDeveloper\Tools\ColorAnalyzer\ColorContext;

/**
 * Displays whether or not the color pairing is WCAG2 compliant, to the 
 * specified level.
 * 
 * @package color_reporting
 */
class Wcag2ComplianceReport implements ReportComponent {

  /**
   * Pass to the constructor to use AA criteria.
   */
  const LEVEL_AA = 'aa';
  
  /**
   * Pass to the constructor to use AAA criteria.
   */
  const LEVEL_AAA = 'aaa';
  
  /**
   * @var string
   */
  private $columnName;
  
  /**
   * @var string
   */
  private $complianceLevel;

  /**
   * @param string $name
   * @param string $complianceLevel
   *   Either LEVEL_AA or LEVEL_AAA
   */
  public function __construct($name, $complianceLevel) {
    $this->columnName = $name;
    $this->complianceLevel = $complianceLevel;
  }
  
  public function columnHeader() {
    return $this->columnName;
  }
  
  public function columnValue(ColorContext $color) {
    switch ($this->complianceLevel) {
      case self::LEVEL_AA:
        return $this->calculateWcagCompliance($color, 4.5, 3);
      case self::LEVEL_AAA:
        return $this->calculateWcagCompliance($color, 7, 4.5);
    }
    return NULL;
  }
  
  /**
   * The WCAG specification specifies two thresholds for AA and AAA compliance.
   * One specifies the threshold for large text, the other is for any sized
   * text. AA and AAA specify different thresholds.
   * 
   * @param ColorContext $color
   *   The color to analyze.
   * @param float $anySizeThreshold
   *   The minimum threshold for luminosity for any text.
   * @param float $largeTextThreshold
   *   The minimum threshold for luminosity for large text.
   * 
   * @return string
   */
  private function calculateWcagCompliance(ColorContext $color, $anySizeThreshold, $largeTextThreshold) {
    if ($color->diffLuminosity() >= $anySizeThreshold) {
      return 'Yes';
    }
    elseif ($color->diffLuminosity() >= $largeTextThreshold) {
      return 'Large';
    }
    return 'No';
  }
  
}
