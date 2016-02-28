<?php 

namespace TS\Restclient\Client;

/***************************************************************
*  Copyright notice
*
*  (c) 2015 Tommaso Sacramone <tommasosacramone.www@gmail.com>
*  
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * HttpClient Response 
 *
 * This class is instantiated and setting from HttpClient as response of a request.
 */
class HttpClientResponse {  
  
  /**   
   * @var string The header
   */
  protected $header;
  
  /**   
   * @var string The body
   */
  protected $body;    
  
  /**   
   * @var int The request size
   */
  protected $requestSize;    
  
  /**   
   * @var string The content type
   */
  protected $contentType;
  
  /**   
   * @var int The http code
   */
  protected $httpCode;
  
  /**   
   * @var float The total time
   */
  protected $totalTime;
  
  /**   
   * @var float The connection time
   */
  protected $connectTime;
  
  /**   
   * @var string The primary ip
   */
  protected $primaryIp;
  
  /**   
   * @var string The primary port
   */
  protected $primaryPort;
  
  /**
   * Constructor   
   */
  public function __construct($header, $body, $requestSize, $contentType, $httpCode, $totalTime, $connectTime, $primaryIp, $primaryPort) {
    $this -> header = $header;    
    $this -> body = $body;
    $this -> requestSize = $requestSize;
    $this -> contentType = $contentType;
    $this -> httpCode = $httpCode;
    $this -> totalTime = $totalTime;
    $this -> connectTime = $connectTime;
    $this -> primaryIp = $primaryIp;
    $this -> primaryPort = $primaryPort;
  }
  
  /**
   * Get the header
   *   
   * @return mixed The header
   */
  public function getHeader($toArray = false) {
    $header = $this -> header;    
    if (isset($toArray) && $toArray === true) {
      $headerToarray = array();
      foreach (explode("\r\n", $header) as $index => $row) {  
        if ($index === 0) {
          $headerToArray['Status-Code'] = $row;
        }
        else {          
          list ($k, $v) = explode(': ', $row);
          $headerToArray[$k] = $v;          
        }        
      }
      
      $header = $headerToArray;
    }
    return $header;  
  }
  
  /**
   * Get the body
   *
   * @return mixed The body
   */
  public function getBody() {
    $body = $this -> body;    
    return $body;  
  }
  
  /**
   * Get the request size
   *   
   * @return int The request size
   */
  public function getRequestSize() {
    return $this -> requestSize;  
  }
  
  /**
   * Get the content type
   *   
   * @return string The Content Type
   */
  public function getContentType() {
    return $this -> contentType;  
  }
  
  /**
   * Get the http code
   *   
   * @return int The http code
   */
  public function getHttpCode() {
    return $this -> httpCode;  
  }
  
  /**
   * Get the total time
   *   
   * @return float The total time
   */
  public function getTotalTime() {
    return $this -> totalTime;  
  }
  
  /**
   * Get the connect time
   *   
   * @return float The connect time
   */
  public function getConnectTime() {
    return $this -> connectTime;  
  }
  
  /**
   * Get the primary ip
   *   
   * @return string The primary ip
   */
  public function getPrimaryIp() {
    return $this -> primaryIp;  
  }
  
  /**
   * Get the primary port
   *   
   * @return string The primary port
   */
  public function getPrimaryPort() {
    return $this -> primaryPort;  
  }
  
  
   
}

?>
