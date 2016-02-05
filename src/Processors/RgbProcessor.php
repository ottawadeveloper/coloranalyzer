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
 * Responsible for transforming an rgb() or rgba() function call into a Color 
 * object.
 * 
 * @package color_processing
 */
class RgbProcessor extends AbstractCssFunctionProcessor {

  public function formatColor(Color $color) {
    if ($color->alpha() < 1) {
      return $this->formatColorFunction('rgba', array(
        $color->red(),
        $color->green(),
        $color->blue(),
        round($color->alpha(), 2),
      ));
    }
    else {
      return $this->formatColorFunction('rgb', array(
        $color->red(),
        $color->green(),
        $color->blue(),
      ));
    }
  }
  
  public function buildColor($colorString) {
    $cssFunction = $this->parseCssColorFunction($colorString);
    if (empty($cssFunction)) {
      return NULL;
    }
    switch ($cssFunction['name']) {
      case 'rgb':
        if (count($cssFunction['arguments']) !== 3) {
          return NULL;
        }
        return new Color(
          $cssFunction['arguments'][0],
          $cssFunction['arguments'][1],
          $cssFunction['arguments'][2]
        );
      case 'rgba':
        if (count($cssFunction['arguments']) !== 4) {
          return NULL;
        }
        return new Color(
          $cssFunction['arguments'][0],
          $cssFunction['arguments'][1],
          $cssFunction['arguments'][2],
          round($cssFunction['arguments'][3] * 255)
        );
    }
    return NULL;
  }
  
}
