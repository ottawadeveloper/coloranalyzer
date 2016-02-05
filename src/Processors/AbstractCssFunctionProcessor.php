<?php
/**
 * @file
 * Provides the AbstractCssFunctionProcessor class.
 * 
 * @package color_processing
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Processors;

/**
 * Parent class for processors that work with CSS functions for color.
 * 
 * @package color_processing
 */
abstract class AbstractCssFunctionProcessor implements \OttawaDeveloper\Tools\ColorAnalyzer\ColorProcessor {
  
  /**
   * Converts the text of a CSS function (like rgb() or hsl()) into a meaningful
   * array.
   * 
   * @param string $string
   *   A CSS function call
   * 
   * @return array
   *   An array with two elements, or NULL if the string is not a valid 
   *   function.
   *   - name: The name of the function.
   *   - arguments: An array of arguments that were passed to the function.
   */
  protected function parseCssColorFunction($string) {
    $firstBrace = strpos($string, '(');
    if ($firstBrace === FALSE) {
      return NULL;
    }
    $cssFunction = array(
      'name' => strtolower(substr($string, 0, $firstBrace)),
      'arguments' => array(),
    );
    $lastBrace = strpos($string, ')');
    $argumentString = substr($string, $firstBrace + 1, $lastBrace - $firstBrace - 1);
    $rawArguments = explode(',', $argumentString);
    array_walk($rawArguments, 'trim');
    $cssFunction['arguments'] = $rawArguments;
    return $cssFunction;
  }
  
  /**
   * Assembles a CSS function call from name and arguments.
   * 
   * @param string $functionName
   * @param array $args
   * 
   * @return string
   */
  protected function formatColorFunction($functionName, $args) {
    return $functionName . '(' . implode(', ', $args) . ')';
  }
  
}