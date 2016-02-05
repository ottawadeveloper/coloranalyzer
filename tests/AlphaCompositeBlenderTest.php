<?php

namespace OttawaDeveloper\Tools\ColorAnalyzer\Tests;

use OttawaDeveloper\Tools\ColorAnalyzer\Processors\AlphaCompositeBlender;
use OttawaDeveloper\Tools\ColorAnalyzer\Color;

class AlphaCompositeBlenderTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider blendTests
   */
  public function testColorBlend(Color $foreground, Color $background, Color $expectedResult) {
    $blender = new AlphaCompositeBlender();
    $result = $blender->blendColors($foreground, $background);
    $this->assertEquals($expectedResult->red(), $result->red());
    $this->assertEquals($expectedResult->green(), $result->green());
    $this->assertEquals($expectedResult->blue(), $result->blue());
    $diff = abs($expectedResult->alpha() - $result->alpha());
    $this->assertLessThan(0.01, $diff);
  }
  
  public function blendTests() {
    return [
      [new Color(0, 0, 0, 0.25), new Color(255, 0, 0, 1), new Color(191, 0, 0, 1)],
      [new Color(126, 120, 115, 1), new Color(255, 255, 255, 1), new Color(126, 120, 115, 1)],
      [new Color(50, 45, 40, 0), new Color(90, 80, 70, 1), new Color(90, 80, 70, 1)],
    ];
  }
  
}
