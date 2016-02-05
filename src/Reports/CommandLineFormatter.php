<?php
/**
 * @file
 * Provides the CommandLineFormatter class.
 * 
 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

/**
 * Handles report generation for command line applications.
 * 
 * @package color_reporting
 */
class CommandLineFormatter implements ReportFormatter {

  /**
   * An array of information about the columns that helps in formatting them.
   * The array is keyed by the same keys as the ReportRunner gives the 
   * ReportComponents (numeric, in ascending order as they are added). Inside
   * each entry is another array with the following keys:
   * 
   * - align: Can be either "left" or "right"
   * - width: The width, in characters.
   * 
   * @var array
   */
  private $columnInfo = array();
  
  /**
   * @param array $columnWidths
   *   Default values for $columnInfo that will override any calculated values.
   *   The default values if you do not specify any are:
   *   - align: left
   *   - width: twice the width of the header
   */
  public function __construct(array $columnWidths = array()) {
    $this->columnInfo = $columnWidths;
    foreach ($this->columnInfo as $key => $value) {
      if (is_numeric($value)) {
        $this->columnInfo[$key] = array(
          'width' => $value,
        );
      }
      $this->columnInfo[$key] += array(
        'width' => NULL,
        'align' => 'left',
      );
    }
  }
  
  public function start() {}
  
  public function formatHeader(array $header) {
    $first = TRUE;
    $totalLength = 0;
    foreach ($header as $key => $headerName) {
      if (empty($this->columnInfo[$key]['width']) || $this->columnInfo[$key]['width'] <= strlen($headerName) + 1) {
        $this->columnInfo[$key] = strlen($headerName) * 2;
      }
      $totalLength += $this->columnInfo[$key]['width'];
      $this->displayCell($headerName, $key, $first);
      $first = FALSE;
    }
    echo PHP_EOL;
    echo str_repeat('-', $totalLength);
    echo PHP_EOL;
  }
  
  public function formatRow(array $row) {
    $first = TRUE;
    foreach ($row as $key => $value) {
      $this->displayCell($value, $key, $first);
      $first = FALSE;
    }
    echo PHP_EOL;
  }
  
  /**
   * @param mixed $value
   *   The value to display in the cell.
   * @param mixed $key
   *   The key that is associated with the column.
   * @param boolean $isFirst
   *   Whether or not it is the first column in the row.
   */
  private function displayCell($value, $key, $isFirst = FALSE) {
    if ($isFirst) {
      echo ' | ';
    }
    echo str_pad($value, $this->columnInfo[$key]['width'], ' ', $this->columnInfo[$key]['align'] == 'right' ? STR_PAD_LEFT : STR_PAD_RIGHT);
  }
    
  
  public function end() {}
  
}
