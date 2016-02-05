<?php
/**
 * @file
 * Provides the AlphaCompositeBlender class.
 * 
 * @package color_processing
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Processors;

/**
 * Responsible for blending two colors together using the standard alpha
 * composite method.
 * 
 * @package color_processing
 */
class AlphaCompositeBlender implements \OttawaDeveloper\Tools\ColorAnalyzer\ColorBlender {

  public function blendColors(\OttawaDeveloper\Tools\ColorAnalyzer\ColorContext $context) {
    $convertedBackgroundAlpha = $context->background()->alpha() * (1 - $context->foreground()->alpha());
    $resultAlpha = $context->foreground()->alpha() + ($convertedBackgroundAlpha);
    // Tiny result alphas means that the color is transparent, so we just
    // return a transparent color.
    if ($resultAlpha < 0.001) {
      return new \OttawaDeveloper\Tools\ColorAnalyzer\BlendedColor($context->foreground(), $context->background(), 0, 0, 0, 0);
    }
    return new \OttawaDeveloper\Tools\ColorAnalyzer\BlendedColor(
      $context->foreground(),
      $context->background(),
      $this->blendColorComponent(
          $context->foreground()->red(), 
          $context->background()->red(), 
          $context->foreground()->alpha(), 
          $context->background()->alpha(), 
          $resultAlpha
      ),
      $this->blendColorComponent(
          $context->foreground()->green(), 
          $context->background()->green(), 
          $context->foreground()->alpha(), 
          $context->background()->alpha(), 
          $resultAlpha
      ),
      $this->blendColorComponent(
          $context->foreground()->blue(), 
          $context->background()->blue(), 
          $context->foreground()->alpha(), 
          $context->background()->alpha(), 
          $resultAlpha
      ),
      $resultAlpha
    );
  }
  
  /**
   * Blends a single component of an RGB color together.
   * 
   * @param int $foregroundComponent
   *   The value of the foreground component, between 0 and 255.
   * @param int $backgroundComponent
   *   The value of the background component, between 0 and 255.
   * @param float $foregroundAlpha
   *   The alpha value of the foreground color, between 0 and 1.
   * @param float $backgroundAlpha
   *   The alpha value of the background color, between 0 and 1.
   * @param float $resultAlpha
   *   The calculated alpha of the final color.
   * 
   * @return int
   */
  private function blendColorComponent($foregroundComponent, $backgroundComponent, $foregroundAlpha, $backgroundAlpha, $resultAlpha) {
    $resultComponent = $foregroundComponent * $foregroundAlpha;
    $resultComponent += $backgroundComponent * $backgroundAlpha * (1 - $foregroundAlpha);
    return (int) round($resultComponent / $resultAlpha);
  }
  
}
