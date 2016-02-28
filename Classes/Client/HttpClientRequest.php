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
 * HttpClient Request 
 *
 * This class is instantiated to execute a request from HttpClient.
 */
class HttpClientRequest {
  
  const METHOD_GET = 'GET';
  const METHOD_POST = 'POST';
  const METHOD_PUT = 'PUT';
  const METHOD_DELETE = 'DELETE';
  
  /**   
   * @var string The method
   */
  protected $method;
  
  /**   
   * @var string The url
   */
  protected $url;    
  
  /**   
   * @var array The header
   */
  protected $header;    
  
  /**   
   * @var array|string The data 
   */
  protected $data;
  
  /**   
   * @var array The files
   */
  protected $files;  
  
  /**
   * Get the method
   *   
   * @return string The method
   */
  public function getMethod() {    
    return $this -> method;
  }
  
  /**
   * Set the method
   *
   * @param string The method  
   * @return HttpClientRequest The current instance
   */
  public function setMethod($method) {
    $this -> method = $method;
    return $this;  
  }
  
  /**
   * Get the url
   *   
   * @return string The url
   */
  public function getUrl() {    
    return $this -> url;
  }
  
  /**
   * Set the url
   *
   * @param string The url  
   * @return HttpClientRequest The current instance
   */
  public function setUrl($url) {
    $this -> url = $url;
    return $this;  
  }
  
  /**
   * Get the header
   *   
   * @return array The header
   */
  public function getHeader() {    
    return $this -> header;
  }
  
  /**
   * Add headers
   *
   * @param array The header  
   * @return HttpClientRequest The current instance
   */
  public function addHeaders($headers) {
    if (!isset($this -> header)) {
      $this -> header = array();
    }
    $this -> header = array_merge($this -> header, $headers);
    
    return $this;  
  }
  
  /**
   * Set the header
   *
   * @param array The header  
   * @return HttpClientRequest The current instance
   */
  public function setHeader($header) {
    $this -> header = $header;
    return $this;  
  }
  
  /**
   * Reset the header
   *   
   * @return HttpClientRequest The current instance
   */
  public function resetHeader() {
    unset($this -> header);
    return $this;  
  }

  /**
   * Get the data
   *   
   * @return array|string The data
   */
  public function getData() {    
    return $this -> data;
  }  
 
  /**
   * Reset the data
   *   
   * @return HttpClientRequest The current instance
   */
  public function resetData() {
    unset($this -> data);
    return $this;  
  }
  
  /**
   * Set the data
   *
   * @param array|string The data  
   * @return HttpClientRequest The current instance
   */
  public function setData($data) {
    $this -> data = $data;
    return $this;  
  }
  
  /**
   * Get the files
   *   
   * @return array The files
   */
  public function getFiles() {    
    return $this -> files;
  }
  
  /**
   * Add files
   *
   * @param array The files  
   * @return HttpClientRequest The current instance
   */
  public function addFiles($files) {
    if (!isset($this -> files)) {
      $this -> files = array();
    }
    $this -> files = array_merge($this -> files, $files);
    
    return $this;  
  }
  
  /**
   * Set the files
   *
   * @param array The files ex. array('file1'=> array('filename'=> 'file1.txt', 'realpath'=> '/path/to/file1', 'mimetype'=>'txt'), 'file2'=> array('filename'=> 'file2.txt', 'realpath'=> '/path/to/file2', 'mimetype'=>'txt'), ...)
   * @return HttpClientRequest The current instance
   */
  public function setFiles($files) {
    $this -> files = $files;
    return $this;  
  }
  
  /**
   * Reset the files
   *   
   * @return HttpClientRequest The current instance
   */
  public function resetFiles() {
    unset($this -> files);
    return $this;  
  }  
   
}

?>
