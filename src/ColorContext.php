<?php
/**
 * @file
 * Provides the ColorContext class.
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Represents a pair of colors, one as foreground and one as background.
 */
class ColorContext {
  
  /**
   * @var \OttawaDeveloper\Tools\ColorAnalyzer\Color
   */
  private $foreground;
  
  /**
   * @var \OttawaDeveloper\Tools\ColorAnalyzer\Color
   */
  private $background;
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $foreground
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $background
   */
  public function __construct(Color $foreground, Color $background) {
    $this->foreground = $foreground;
    $this->background = $background;
  }
  
  /**
   * @return \OttawaDeveloper\Tools\ColorAnalyzer\Color
   */
  public function foreground() {
    return $this->foreground;
  }
  
  /**
   * @return \OttawaDeveloper\Tools\ColorAnalyzer\Color
   */
  public function background() {
    return $this->background;
  }
  
  /**
   * Calculates the difference between the foreground and background colors.
   * 
   * @return int
   * 
   * @see \OttawaDeveloper\Tools\ColorAnalyzer\Color::difference()
   */
  public function difference() {
    return $this->foreground()->difference($this->background());
  }
  
  /**
   * Calculates the difference in luminosity between the foreground and
   * background colors.
   * 
   * @return float
   * 
   * @see \OttawaDeveloper\Tools\ColorAnalyzer\Color::diffLuminosity()
   */
  public function diffLuminosity() {
    return $this->foreground()->diffLuminosity($this->background());
  }
  
  /**
   * Calculates the difference in brightness between the foreground and
   * background colors.
   * 
   * @return int
   * 
   * @see \OttawaDeveloper\Tools\ColorAnalyzer\Color::diffBrightness()
   */
  public function diffBrightness() {
    return $this->foreground()->diffBrightness($this->background());
  }
  
  public function __toString() {
    return (string) $this->foreground() . ' on ' . (string) $this->background();
  }
  
}
