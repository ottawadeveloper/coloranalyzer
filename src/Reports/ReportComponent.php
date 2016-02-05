<?php
/**
 * @file
 * Provides the ReportComponent interface.
 * 
 * @package color_reporting
 */

namespace OttawaDeveloper\Tools\ColorAnalyzer\Reports;

use OttawaDeveloper\Tools\ColorAnalyzer\ColorContext;

/**
 * Implementations of this class are used to provide information for a report
 * formatter. Each implementation should provide it's own header, as well as
 * being able to provide the value for a given ColorContext object.
 * 
 * @package color_reporting
 */
interface ReportComponent {

  public function columnHeader();
  
  public function columnValue(ColorContext $color);
  
}
