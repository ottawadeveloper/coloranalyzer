<?php
/**
 * @file
 * Provides the ColorFactory class.
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Responsible for building colors from strings.
 * 
 * Leverages ColorProcessor objects to identify different ways of parsing color
 * strings.
 * 
 * @package wcag_color
 */
class ColorFactory {
  
  /**
   * @var array 
   */
  private $processors = array();
  
  /**
   * @var ColorBlender
   */
  private $blendStrategy = NULL;
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\ColorBlender $blender
   */
  public function __construct(ColorBlender $blender = NULL) {
    if (empty($blender)) {
      $blender = new Processors\AlphaCompositeBlender();
    }
    $this->blendStrategy = $blender;
  }
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\ColorBlender $blender
   */
  public function setColorBlender(ColorBlender $blender) {
    $this->blendStrategy = $blender;
  }
  
  /**
   * @param ColorProcessor $processor 
   */
  public function registerProcessor(ColorProcessor $processor) {
    $this->processors[] = $processor;
  }
  
  /**
   * @param string $colorString
   * 
   * @return Color
   *   The Color object that matches $colorString. If no object could be created
   *   (e.g. the $colorString is not understood), returns NULL.
   */
  public function buildColor($colorString) {
    foreach ($this->processors as $processor) {
      $processorColor = $processor->buildColor($colorString);
      if (!empty($processorColor)) {
        return $processorColor;
      }
    }
    return NULL;
  }
  
  /**
   * @param string $colorString
   * 
   * @return Color
   *   The Color object that matches $colorString. If no object could be created
   *   (e.g. the $colorString is not understood), returns NULL.
   * 
   * @param ColorContext $color
   */
  public function blendColors(ColorContext $colorPair) {
    return $this->blendStrategy->blendColors($colorPair);
  }
  
}
