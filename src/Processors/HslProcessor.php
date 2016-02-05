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
 * Responsible for transforming an hsl() or hsla() function call into a Color 
 * object.
 * 
 * @package color_processing
 */
class HslProcessor extends AbstractCssFunctionProcessor {

  public function buildColor($colorString) {
    $cssFunction = $this->parseCssColorFunction($colorString);
    if (empty($cssFunction)) {
      return NULL;
    }
    switch ($cssFunction['name']) {
      case 'hsl':
        if (count($cssFunction['arguments']) !== 3) {
          return NULL;
        }
        return $this->buildColorFromHsl(
          $cssFunction['arguments'][0] / 1.0,
          $cssFunction['arguments'][1] / 100,
          $cssFunction['arguments'][2] / 100
        );
      case 'hsla':
        if (count($cssFunction['arguments']) !== 4) {
          return NULL;
        }
        return $this->buildColorFromHsl(
          $cssFunction['arguments'][0] / 1.0,
          $cssFunction['arguments'][1] / 100,
          $cssFunction['arguments'][2] / 100,
          $cssFunction['arguments'][3] / 100
        );
    }
    return NULL;
  }
  
  public function formatColor(\OttawaDeveloper\Tools\ColorAnalyzer\Color $color) {
    $colorValues = array(
      'red' => $color->red() / 255,
      'green' => $color->green() / 255,
      'blue' => $color->blue() / 255,
    );
    $minimumValue = min($colorValues);
    $maximumValue = max($colorValues);
    $luminescence = round(($minimumValue + $maximumValue) / 2, 2);
    if ($minimumValue == $maximumValue) {
      return $this->formatHslColor(0, 0, $luminescence, $color->alpha());
    }
    $colorRange = $maximumValue - $minimumValue;
    $saturation = $colorRange;
    if ($luminescence < 0.5) {
      $saturation /= ($maximumValue + $minimumValue);
    }
    else {
      $saturation /= (2 - $colorRange);
    }
    $hue = 0;
    if ($colorValues['red'] >= $colorValues['blue'] && 
      $colorValues['red'] >= $colorValues['green']) {
      $hue = ($colorValues['green'] - $colorValues['blue']) / $colorRange;
    }
    elseif ($colorValues['green'] >= $colorValues['blue'] &&
      $colorValues['green'] >= $colorValues['red']) {
      $hue = 2 + (($colorValues['blue'] - $colorValues['red']) / $colorRange);
    }
    else {
      $hue = 4 + (($colorValues['red'] - $colorValues['green']) / $colorRange);
    }
    return $this->formatHslColor($hue * 60, $saturation, $luminescence, $color->alpha());
  }
  
  /**
   * Responsible for formatting an HSL color once the hue, saturation and
   * luminescence have been determined.
   * 
   * @param int $hue
   *   The hue of the color, as measured in degrees. Values outside of 0-360
   *   will be normalized for you.
   * @param float $saturation
   *   The saturation, between 0 and 1.
   * @param float $luminescence
   *   The luminescence, between 0 and 1.
   * @param float $alpha
   *   The alpha value, between 0 and 1.
   * 
   * @return string
   *   Formatted CSS color string, as hsl() or hsla().
   */
  private function formatHslColor($hue, $saturation, $luminescence, $alpha = 1) {
    while ($hue < 0) {
      $hue += 360;
    }
    while ($hue > 360) {
      $hue -= 360;
    }
    if ($alpha < 1) {
      return $this->formatColorFunction('hsla', array(
        round($hue, 2),
        round($saturation * 100, 2),
        round($luminescence * 100, 2),
        round($alpha, 2),
      ));
    }
    else {
      return $this->formatColorFunction('hsl', array(
        round($hue, 2),
        round($saturation * 100, 2),
        round($luminescence * 100, 2),
      ));
    }
  }
  
  /**
   * Create a Color object from HSL values.
   * 
   * @param int $hue
   *   The hue, as a value between 0 and 360.
   * @param float $saturation
   *   The saturation, as a value between 0 and 1.
   * @param float $lightness
   *   The lightness, as a value between 0 and 1.
   * @param int $alpha
   *   The alpha value between 0 and 255.
   * 
   * @return \OttawaDeveloper\Tools\ColorAnalyzer\Processors\Color
   */
  private function buildColorFromHsl($hue, $saturation, $lightness, $alpha = 1) {
    $hueModifier = $hue / 60.0;
    while ($hueModifier > 2) {
      $hueModifier -= 2;
    }
    $chroma = (1 - abs((2 * $lightness) - 1)) * $saturation;
    $xModifier = $chroma * (1 - abs((($hueModifier) - 1)));
    $lightnessModifier = $lightness - ($chroma / 2);
    $colors = array(
      'red' => 0,
      'green' => 0,
      'blue' => 0,
    );
    if ($hue < 60) {
      $colors['red'] = $chroma;
      $colors['green'] = $xModifier;
    }
    elseif ($hue < 120) {
      $colors['red'] = $xModifier;
      $colors['green'] = $chroma;
    }
    elseif ($hue < 180) {
      $colors['green'] = $chroma;
      $colors['blue'] = $xModifier;
    }
    elseif ($hue < 240) {
      $colors['green'] = $xModifier;
      $colors['blue'] = $chroma;
    }
    elseif ($hue < 300) {
      $colors['red'] = $xModifier;
      $colors['blue'] = $chroma;
    }
    else {
      $colors['red'] = $chroma;
      $colors['blue'] = $xModifier;
    }
    $color = new \OttawaDeveloper\Tools\ColorAnalyzer\Color(
      (int) round(($colors['red'] + $lightnessModifier) * 255),
      (int) round(($colors['green'] + $lightnessModifier) * 255),
      (int) round(($colors['blue'] + $lightnessModifier) * 255),
      $alpha
    );
    return $color;
  }
  
}
