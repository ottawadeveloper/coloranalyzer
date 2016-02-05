<?php
/**
 * @file
 * Provides the ColorTextReport class.
 * 
 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

use OttawaDeveloper\Tools\ColorAnalyzer\ColorContext;

/**
 * Displays the color (either background or foreground), using a specific
 * processor to generate the text display.
 * 
 * @package color_reporting
 */
class ColorTextReport implements ReportComponent {

  /**
   * Pass to the constructor in order to display the background color.
   */
  const BACKGROUND = 'bg';
  
  /**
   * Pass to the constructor in order to display the foreground color.
   */
  const FOREGROUND = 'fg';
  
  /**
   * @var string
   */
  private $columnName;
  
  /**
   * @var \OttawaDeveloper\Tools\ColorAnalyzer\ColorProcessor
   */
  private $formatClass;
  
  /**
   * One of the color layer constants from this class.
   * 
   * @var string
   */
  private $layer;
  
  /**
   * @var boolean
   */
  private $useOriginalColors;
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\ColorProcessor $processor
   *   A processor object to format the color.
   * @param string $name
   *   The name of the column that will be displayed.
   * @param string $layer
   *   Whether to use the foreground or background color.
   * @param boolean $useOriginalColors
   *   If set to FALSE, will show the results of the blending process instead
   *   of the original color with alpha.
   */
  public function __construct(\OttawaDeveloper\Tools\ColorAnalyzer\ColorProcessor $processor, $name, $layer, $useOriginalColors = TRUE) {
    $this->columnName = $name;
    $this->formatClass = $processor;
    $this->layer = $layer;
    $this->useOriginalColors = $useOriginalColors;
  }
  
  public function columnHeader() {
    return $this->columnName;
  }
  
  public function columnValue(ColorContext $color) {
    switch ($this->layer) {
      case self::BACKGROUND:
        return $this->formatClass->formatColor($this->resolveColor($color->background()));
      case self::FOREGROUND:
        return $this->formatClass->formatColor($this->resolveColor($color->foreground()));
    }
    return NULL;
  }
  
  /**
   * Converts BlendedColor objects to Color objects if needed.
   * 
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $color
   * 
   * @return \OttawaDeveloper\Tools\ColorAnalyzer\Color
   */
  private function resolveColor(\OttawaDeveloper\Tools\ColorAnalyzer\Color $color) {
    if ($this->useOriginalColors && ($color instanceof \OttawaDeveloper\Tools\ColorAnalyzer\BlendedColor)) {
      return $color->originalColor();
    }
    else {
      return $color;
    }
  }
  
}
