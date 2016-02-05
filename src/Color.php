<?php
/**
 * @file
 * Provides the Color class. 
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Represents a CSS color string. Responsible for converting from other color
 * formats into the RGBA specification that can be used.
 * 
 * @package wcag_color 
 */
class Color {
  
  /**
   * @var int 
   */
  private $red;
  
  /**
   * @var int 
   */
  private $blue;
  
  /**
   * @var int 
   */
  private $green;
  
  /**
   * @var float
   */
  private $alpha;
  
  /**
   * @param int $red
   * @param int $green
   * @param int $blue 
   * @param float $alpha
   */
  public function __construct($red, $green, $blue, $alpha = 1) {
    $this->red = (int) round($red);
    $this->green = (int) round($green);
    $this->blue = (int) round($blue);
    $this->alpha = round($alpha, 2);
  }
  
  /**
   * @return int 
   */
  public function red() {
    return $this->red;
  }
  
  /**
   * @return int 
   */
  public function blue() {
    return $this->blue;
  }
  
  /**
   * @return int 
   */
  public function green() {
    return $this->green;
  }
  
  /**
   * @return int 
   */
  public function alpha() {
    return $this->alpha;
  }
  
  /**
   * Returns the brightness of the color. Brightness is measured by taking a
   * weighted average of the three color components.
   * 
   * @return float 
   */
  public function brightness() {
    return (
      ($this->red * 299) + 
      ($this->green * 587) + 
      ($this->blue * 114)
    ) / 1000;
  }
  
  /**
   * Calculates the relative luminosity of this color.
   * 
   * @return float
   * 
   * @see https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef 
   */
  public function luminosity() {
    return 0.05 + (
      (0.2126 * $this->luminosityComponent($this->red()))
      + (0.7152 * $this->luminosityComponent($this->green()))
      + (0.0722 * $this->luminosityComponent($this->blue()))
    );
  }
  
  /**
   * Calculates the relative luminosity of the component of a color.
   * 
   * @param int $colorValue
   * 
   * @return float
   * 
   * @see https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef 
   */
  private function luminosityComponent($colorValue) {
    $decimal = $colorValue / 255;
    if ($decimal < 0.3928) {
      return $decimal / 12.92;
    }
    return pow(($decimal + 0.055) / 1.055, 2.4);
  }
  
  /**
   * Calculates the difference between this color and another one.
   * 
   * The difference is defined as the sum of the absolute values of the 
   * differences between the components.
   * 
   * @param Color $color
   * 
   * @return int
   */
  public function difference(Color $color) {
    return abs($this->red() - $color->red()) 
            + abs($this->green() - $color->green()) 
            + abs($this->blue() - $color->blue());
  }
  
  /**
   * Calculates the difference in luminosity between this color and another one.
   * 
   * @param Color $color
   * 
   * @return float
   * 
   * @see https://www.w3.org/TR/UNDERSTANDING-WCAG20/visual-audio-contrast-contrast.html#contrast-ratiodef
   */
  public function diffLuminosity(Color $color) {
    $myLuminosity = $this->luminosity();
    $comparisonLuminosity = $color->luminosity();
    if ($myLuminosity > $comparisonLuminosity) {
      return $myLuminosity / $comparisonLuminosity;
    }
    return $comparisonLuminosity / $myLuminosity;
  }
  
  /**
   * Calculates the difference in brightness between this color and another one.
   * 
   * The difference is defined as the absolute value of the difference in the
   * brightness of the colors.
   * 
   * @param Color $color
   * 
   * @return int 
   */
  public function diffBrightness(Color $color) {
    return abs($this->brightness() - $color->brightness());
  }
  
  public function __toString() {
    return 'r=' . $this->red() . 
      ';g=' . $this->green() .
      ';b=' . $this->blue() . 
      ';a=' . $this->alpha();
  }
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $compareTo
   * 
   * @return boolean
   */
  public function equals(Color $compareTo) {
    return 
      ($compareTo->red() === $this->red())
      && ($compareTo->green() === $this->green())
      && ($compareTo->blue() === $this->blue())
      && ($compareTo->alpha() === $this->alpha());
  }
  
}
