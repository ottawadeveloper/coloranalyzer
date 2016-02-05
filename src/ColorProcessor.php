<?php
/**
 * @file
 * Defines the ColorProcessor interface. 
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Instances of this class are responsible for building colors. 
 * 
 * There are many different kinds of colors that are valid; some examples would
 * include RGB, HSL, and color keywords. To more cleanly separate the logic of
 * each, each strategy for parsing a color string will have its own class.
 * 
 * @see ColorFactory
 * 
 * @package wcag_color
 */
interface ColorProcessor {

  /**
   * @param string $colorString
   * 
   * @return Color
   *   The Color object that matches $colorString. If no object could be created
   *   (e.g. the $colorString is not understood), returns NULL.
   */
  function buildColor($colorString);
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $color
   * 
   * @return string
   */
  function formatColor(Color $color);
  
}
