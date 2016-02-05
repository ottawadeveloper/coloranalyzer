<?php
/**
 * @file
 * Provides the BlendedColor class. 
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * A Color object that was created from two other colors.
 * 
 * @package wcag_color 
 */
class BlendedColor extends Color {
  
  /**
   * @var Color
   */
  private $originalColor;
  
  /**
   * @var Color
   */
  private $blendedWithColor;
  
  /**
   * @param Color $original
   *   The color that was the foreground in the blending process.
   * @param Color $blendedWith
   *   The color that was the background in the blending process.
   * @param int $red
   * @param int $green
   * @param int $blue 
   * @param float $alpha
   */
  public function __construct(Color $original, Color $blendedWith, $red, $green, $blue, $alpha = 1) {
    $this->originalColor = $original;
    $this->blendedWithColor = $blendedWith;
    parent::__construct($red, $green, $blue, $alpha);
  }
  
  public function originalColor() {
    return $this->originalColor;
  }
  
  public function blendedWithColor() {
    return $this->blendedWithColor;
  }
  
}
