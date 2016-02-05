<?php

namespace OttawaDeveloper\Tools\ColorAnalyzer\Tests;

use \OttawaDeveloper\Tools\ColorAnalyzer\Processors\HslProcessor;

class HslProcessorTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider validColors 
   */
  public function testColors($string, $red, $green, $blue, $alpha) {
    $processor = new HslProcessor();
    $color = $processor->buildColor($string);
    $this->assertEquals($red, $color->red());
    $this->assertEquals($green, $color->green());
    $this->assertEquals($blue, $color->blue());
    $alphaDifference = abs($alpha - $color->alpha());
    $this->assertLessThan(0.01, $alphaDifference);
  }
  
  /**
   * @dataProvider invalidColors
   */
  public function testInvalidColors($string) {
    $processor = new HslProcessor();
    $color = $processor->buildColor($string);
    $this->assertNull($color);
  }
  
  
  public function validColors() {
    return [
      ['hsl(0,0,100)',255,255,255,1],
      ['hsl(0,0,50)',128,128,128,1],
      ['hsl(0,0,0)',0,0,0,1],
      ['hsl(0,100,50)',255,0,0,1],
      ['hsl(60,100,37.5)',191,191,0,1],
      ['hsl(120,100,25)',0,128,0,1],
      ['hsl(180,100,75)',128,255,255,1],
      ['hsl(240,100,75)',128,128,255,1],
      ['hsl(300,50,50)',191,64,191,1],
      ['hsl(61.8,63.8,39.3)',160,164,36,1],
      ['hsl(251.1,83.2,51.1)',65,27,234,1],
      ['hsl(134.9,70.7,39.6)',30,172,65,1],
      ['hsl(49.5,89.3,49.7)',240,200,14,1],
      ['hsl(283.7,77.5,54.2)',180,48,229,1],
      ['hsl(14.3,81.7,62.4)',237,118,81,1],
      ['hsl(56.9,99.1,76.5)',254,248,136,1],
      ['hsl(162.4,77.9,44.7)',25,203,151,1],
      ['hsl(248.3,60.1,37.3)',54,38,152,1],
      ['hsl(240.5,29,60.7)',126,126,184,1],
      ['hsla(248.3,60.1,37.3, 100)',54,38,152,1],
      ['hsla(240.5,29,60.7, 0)',126,126,184,0],
      ['hsla(248.3,60.1,37.3, 47.1)',54,38,152,.471],
      ['hsla(240.5,29,60.7, 78.4)',126,126,184,.784],
    ];
  }
  
  public function invalidColors() {
    return [
      ['000000'],
      ['rgb(0, 0, 0)'],
      ['hsl(20, 20)'],
      ['hsla(50, 50, 50)'],
    ];
  }
  
}
