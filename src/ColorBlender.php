<?php
/**
 * @file
 * Defines the ColorBlender interface. 
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Instances of this class are responsible for blending two colors together.
 * 
 * @see ColorFactory
 * 
 * @package wcag_color
 */
interface ColorBlender {

  /**
   * @param string $colorString
   * 
   * @return BlendedColor
   *   The Color object that matches $colorString. If no object could be created
   *   (e.g. the $colorString is not understood), returns NULL.
   * 
   * @param ColorContext $color
   */
  function blendColors(ColorContext $color);
  
}
