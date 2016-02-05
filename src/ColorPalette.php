<?php
/**
 * @file
 * Provides the ColorCombiner class.
 * 
 * @package wcag_color
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer;

/**
 * Transforms an array of colors into a set of foreground/background color 
 * pairs, ignoring duplicates and preventing the pairing of the same color to 
 * itself. 
 * 
 * This class is designed to be used in reporting the WCAG compliance of
 * foreground colors with background colors, by turning a list of colors into a
 * set of matched foreground/background pairs.
 * 
 * @package wcag_color
 */
class ColorPalette implements \Iterator {
  
  /**
   * An array of ColorContext objects, keyed by a value unique to the color
   * combination.
   * 
   * @var array
   */
  private $colorPairs = array();
  
  /**
   * An array of the string keys from $colorPairs, numerically indexed.
   * 
   * @var array
   */
  private $pairKeys = array();
  
  /**
   * @var int
   */
  private $currentPosition = 0;
  
  /**
   * @var ColorFactory
   */
  private $colorFactory;
  
  /**
   * An array of strings that $colorFactory could not process into colors.
   * 
   * @var array
   */
  private $invalidStrings = array();
  
  /**
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\ColorFactory $colorFactory
   * @param array $colorStrings
   *   An array of either strings that can be processed by the color factory,
   *   or Color objects. Invalid strings can be found by using 
   *   getInvalidStrings().
   */
  public function __construct(ColorFactory $colorFactory, array $colorStrings) {
    $this->colorFactory = $colorFactory;
    $this->pairColorArray($this->buildColors($colorStrings)); 
  }
  
  public function sortColorArray($function) {
    uasort($this->colorPairs, $function);
    $this->pairKeys = array_keys($this->colorPairs);
  }
  
  /**
   * Creates the $colorPairs array by looping through all of the colors and 
   * pairing them.
   * 
   * @param array $colors
   *   An array of Color objects.
   * 
   * @see pairColors()
   */
  private function pairColorArray(array $colors) {
    foreach ($colors as $background) {
      foreach ($colors as $foreground) {
        $this->pairColors($foreground, $background);
      }
    }
    $this->pairKeys = array_keys($this->colorPairs);
  }
  
  /**
   * Performs the work in pairing two colors, according to these rules.
   * 
   * - Colors cannot be paired with themselves.
   * - Color pairs with transparent background are added twice; once by blending
   *   the background with white, and once with black.
   * - Color pairs with transparent foregrounds have the foreground color
   *   blended with the background color first.
   *
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $foreground
   * @param \OttawaDeveloper\Tools\ColorAnalyzer\Color $background
   */
  private function pairColors(Color $foreground, Color $background) {
    if ($foreground->equals($background)) {
      return;
    }
    if ($background->alpha() < 1) {
      $this->pairColors($foreground, $this->colorFactory->blendColors(new ColorContext($background, new Color(255, 255, 255, 1))));
      $this->pairColors($foreground, $this->colorFactory->blendColors(new ColorContext($background, new Color(0, 0, 0, 1))));
      return;
    }
    if ($foreground->alpha() < 1) {
      $this->pairColors($this->colorFactory->blendColors(new ColorContext($foreground, $background)), $background);
      return;
    }
    $context = new ColorContext($foreground, $background);
    $this->colorPairs[(string) $context] = $context;
  }
  
  /**
   * Constructs an array of Color objects by using $colorFactory to process
   * strings. Unrecognized strings are added to $invalidStrings.
   * 
   * @param array $colorStrings
   * 
   * @return array
   *   An array of Color objects.
   */
  private function buildColors(array $colorStrings) {
    $colors = array();
    foreach ($colorStrings as $colorString) {
      if ($colorString instanceof Color) {
        $colors[(String) $colorString] = $colorString;
        continue;
      }
      $color = $this->colorFactory->buildColor($colorString);
      if (!empty($color)) {
        $colors[(string) $color] = $color;
      }
      else {
        $this->invalidStrings[] = (string) $colorString;
      }
    }
    return $colors;
  }
  
  /**
   * @return array
   *   An array of strings that the ColorFactory that was injected could not
   *   process.
   */
  public function getInvalidStrings() {
    return $this->invalidStrings;
  }
  
  public function next() {
    $this->currentPosition++;
  }
  
  public function valid() {
    return $this->currentPosition < count($this->pairKeys);
  }
  
  public function key() {
    return $this->pairKeys[$this->currentPosition];
  }
  
  public function rewind() {
    $this->currentPosition = 0;
  }
  
  public function current() {
    return $this->colorPairs[$this->pairKeys[$this->currentPosition]];
  }
  
}
