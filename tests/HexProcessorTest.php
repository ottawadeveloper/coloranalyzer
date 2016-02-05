<?php

namespace OttawaDeveloper\Tools\ColorAnalyzer\Tests;

use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\HexProcessor;

class HexProcessorTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider validColors 
   */
  public function testColors($string, $red, $green, $blue, $alpha) {
    $processor = new HexProcessor();
    $color = $processor->buildColor($string);
    $this->assertEquals($red, $color->red());
    $this->assertEquals($green, $color->green());
    $this->assertEquals($blue, $color->blue());
    $diff = ($alpha / 255) - $color->alpha();
    $this->assertLessThan(0.01, $diff);
  }
  
  /**
   * @dataProvider invalidColors
   */
  public function testInvalidColors($string) {
    $processor = new HexProcessor();
    $color = $processor->buildColor($string);
    $this->assertNull($color);
  }
  
  
  public function validColors() {
    return [
      ['#000000', 0, 0, 0, 255],
      ['#ff0000', 255, 0, 0, 255],
      ['#FFFFFF', 255, 255, 255, 255],
      ['#00ff00', 0, 255, 0, 255],
      ['#0000ff', 0, 0, 255, 255],
      ['#ff000000', 255, 0, 0, 0],
      ['#ff000066', 255, 0, 0, 102],
      ['#ff', 255, 255, 255, 255],
      ['#369', 51, 102, 153, 255],
      ['#36', 54, 54, 54, 255],
      ['000000', 0, 0, 0, 255],
      ['ff0000', 255, 0, 0, 255],
      ['FFFFFF', 255, 255, 255, 255],
      ['00ff00', 0, 255, 0, 255],
      ['0000ff', 0, 0, 255, 255],
      ['ff000000', 255, 0, 0, 0],
      ['ff000066', 255, 0, 0, 102],
      ['ff', 255, 255, 255, 255],
      ['369', 51, 102, 153, 255],
      ['36', 54, 54, 54, 255],
      [369, 51, 102, 153, 255],
    ];
  }
  
  public function invalidColors() {
    return [
      ['0000'],
      ['00000'],
      ['0000000'],
      ['0'],
      [''],
      ['cdefgh'],
      ['abcde-'],
    ];
  }
  
}
