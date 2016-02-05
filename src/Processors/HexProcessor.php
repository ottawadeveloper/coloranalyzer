<?php
/**
 * @file
 * Provides the HexProcessor class.
 * 
 * @package color_processing
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Processors;

use \OttawaDeveloper\Tools\ColorAnalyzer\Color;

/**
 * Responsible for transforming a color string in the following patterns
 * into a Color object. They may be optionally prefixed by a single # symbol.
 * 
 * - Two-digit hex codes (interpreted as a shade of grey)
 * - Three-digit hex codes (interpreted as web-safe colors)
 * - Six-digit hex codes (interpreted as RGB components in hex)
 * - Eight-digit hex codes (interpreted as RGBA components in hex)
 * 
 * @package color_processing
 */
class HexProcessor implements \OttawaDeveloper\Tools\ColorAnalyzer\ColorProcessor {

  public function formatColor(\OttawaDeveloper\Tools\ColorAnalyzer\Color $color) {
    $string = '#';
    $string .= str_pad(dechex($color->red()), 2, '0', STR_PAD_LEFT);
    $string .= str_pad(dechex($color->green()), 2, '0', STR_PAD_LEFT);
    $string .= str_pad(dechex($color->blue()), 2, '0', STR_PAD_LEFT);
    if ($color->alpha() < 1) {
      $string .= str_pad(dechex((int) round($color->alpha() * 255)), 2, '0', STR_PAD_LEFT);
    }
    return strtoupper($string);
  }
  
  public function buildColor($colorString) {
    if (!preg_match('`^#{0,1}[0-9A-F]{2,8}$`i', $colorString)) {
      return NULL;
    }
    $colorString = ltrim($colorString, '#');
    switch (strlen($colorString)) {
      case 2:
        return $this->buildColorFromHex(
          $colorString, 
          $colorString, 
          $colorString
        );
      case 3:
        return $this->buildColorFromHex(
          str_repeat(substr($colorString, 0, 1), 2),
          str_repeat(substr($colorString, 1, 1), 2),
          str_repeat(substr($colorString, 2, 1), 2)
        );  
      case 6:
        return $this->buildColorFromHex(
          substr($colorString, 0, 2),
          substr($colorString, 2, 2),
          substr($colorString, 4, 2)
        );
      case 8:
        return $this->buildColorFromHex(
          substr($colorString, 0, 2),
          substr($colorString, 2, 2),
          substr($colorString, 4, 2),
          substr($colorString, 6, 2)
        );
    }
    return NULL;
  }
  
  /**
   * @param string $red
   *   A red value in hex, from 00 to FF.
   * @param string $green
   *   A green value in hex, from 00 to FF.
   * @param string $blue
   *   A blue value in hex, from 00 to FF.
   * @param string $alpha
   *   An alpha value in hex, from 00 to FF.
   * 
   * @return \OttawaDeveloper\Tools\ColorAnalyzer\Color 
   */
  private function buildColorFromHex($red, $green, $blue, $alpha = 'FF') {
    return new \OttawaDeveloper\Tools\ColorAnalyzer\Color(
      hexdec($red),
      hexdec($green),
      hexdec($blue),
      hexdec($alpha) / 255
    );
  }
  
}
