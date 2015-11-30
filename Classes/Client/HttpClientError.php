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
 * HttpClient Error 
 *
 * This class is instantiated from HttpClient if an error occurs.
 */
class HttpClientError {  
  
  /**   
   * @var int The code error
   */
  protected $code;
  
  /**   
   * @var string The message error
   */
  protected $message;      
  
  /**
   * Constructor   
   */
  public function __construct($code, $message) {
    $this -> code = $code;    
    $this -> message = $message;        
  }
  
  /**
   * Get the error code
   *   
   * @return int The error code
   */
  public function getCode() {
    return $this -> code;  
  }
  
  /**
   * Get the error message
   *   
   * @return string The error message
   */
  public function getMessage() {    
    return $this -> message;  
  }  
  
}

?>
