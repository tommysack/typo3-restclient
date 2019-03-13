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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/**
 * REST client
 *
 * This class can be used to consume RESTful webservices.
 * HttpClient uses cURL to support the CRUD operations: GET, POST, PUT, DELETE.
 */
class HttpClient {

  const LOGGER_SEVERITY_INFO = 1;
  const LOGGER_SEVERITY_WARNING = 2;
  const LOGGER_SEVERITY_ERROR = 3;
  const LOGGER_SEVERITY_CRITICAL = 4;

  const ERROR_CODE_INSTANCE = -2;
  const ERROR_CODE_OPTIONS = -4;
  const ERROR_CODE_METHOD = -8;
  const ERROR_CODE_EXEC = -16;

  const SETTINGS_KEY_LOG = 'log';
  const SETTINGS_KEY_EXCEPTION = 'exception';
  const SETTINGS_KEY_CHECK_HTTPCODE = 'check_httpcode';
  const SETTINGS_KEY_CHECK_HTTPCODE_VALUE = 400;
  const SETTINGS_KEY_CONNECTION_TIMEOUT = 'connection_timeout';
  const SETTINGS_KEY_TIMEOUT = 'timeout';
  const SETTINGS_KEY_FOLLOW_REDIRECT = 'follow_redirect';
  const SETTINGS_KEY_FOLLOW_MAX_REDIRECT = 'follow_max_redirect';
  const SETTINGS_KEY_PROXY_ADDRESS = 'proxy_address';
  const SETTINGS_KEY_PROXY_PORT = 'proxy_port';
  const SETTINGS_KEY_PROXY_CREDENTIALS = 'proxy_credentials';
  const SETTINGS_KEY_PROXY_AUTH_SCHEME = 'proxy_auth_scheme';
  const SETTINGS_KEY_USER_AGENT = 'user_agent';
  const SETTINGS_KEY_SSL_CHECK_DISABLED = 'ssl_check_disabled';
  const SETTINGS_KEY_SSL_CERTIFICATE_PATH = 'ssl_certificate_path';
  const SETTINGS_KEY_COOKIE_FILE_PATH = 'cookie_file_path';

  const REQUEST_METHOD_GET = 'GET';
  const REQUEST_METHOD_POST = 'POST';
  const REQUEST_METHOD_PUT = 'PUT';
  const REQUEST_METHOD_DELETE = 'DELETE';

  const RESPONSE_KEY_HEADER = 'header';
  const RESPONSE_KEY_BODY = 'body';
  const RESPONSE_KEY_REQUEST_SIZE = 'request_size';
  const RESPONSE_KEY_CONTENT_TYPE = 'content_type';
  const RESPONSE_KEY_HTTP_CODE = 'http_code';
  const RESPONSE_KEY_TOTAL_TIME = 'total_time';
  const RESPONSE_KEY_CONNECT_TIME = 'connect_time';
  const RESPONSE_KEY_PRIMARY_IP = 'primary_ip';
  const RESPONSE_KEY_PRIMARY_PORT = 'primary_port';

  /**
   * @var resource The client cURL instance
   */
  protected $client;

  /**
   * @var array The client settings
   */
  protected $settings;

  /**
   * @var \TS\Restclient\Client\HttpClientError The error
   */
  protected $error;

  /**
   * @var \TS\Restclient\Client\HttpClientResponse The response
   */
  protected $response;

  /**
   * @var \TS\Restclient\Client\HttpClientRequest The request
   */
  protected $request;

  /**
   * @var \TYPO3\CMS\Core\Log\LogManager
   */
  protected $logger;

  /**
   * Constructor
   */
  public function __construct() {
    $this -> logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager') -> getLogger(__CLASS__);    
    $this -> initClient();
  }

  /**
   * Reset the client
   *
   * @param bool $initClient It true the client will be initialize with extension configurations
   * @return HttpClient The current instance, or false in an error occours
   */
  public function reset($initClient = true) {

    $this -> error = null;
    $this -> settings = array();
    $this -> response = null;
    $this -> request = null;

    if (isset($initClient) && $initClient === true) {
      $this -> initClient();
    }
    return $this;
  }

  /**
   * Initialize the client
   *
   * @return void
   */
  protected function initClient() {
    
    $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class) -> get('restclient');
    if ($extConf['error_throw_exception'] === "1") {
      $this -> settings[self::SETTINGS_KEY_EXCEPTION] = true;
    }
    if ($extConf['error_log'] !== "") {
      $this -> settings[self::SETTINGS_KEY_LOG] = intval($extConf['error_log']);
    }
    if ($extConf['client_check_httpcode'] === "1") {
      $this -> settings[self::SETTINGS_KEY_CHECK_HTTPCODE] = true;
    }
    if ($extConf['client_connection_timeout'] !== "") {
      $this -> settings[self::SETTINGS_KEY_CONNECTION_TIMEOUT] = intval($extConf['client_connection_timeout']);
    }
    if ($extConf['client_timeout'] !== "") {
      $this -> settings[self::SETTINGS_KEY_TIMEOUT] = intval($extConf['client_timeout']);
    }
    if ($extConf['client_follow_redirect'] === "1") {
      $this -> settings[self::SETTINGS_KEY_FOLLOW_REDIRECT] = true;
    }
    if ($extConf['client_max_redirect'] !== "") {
      $this -> settings[self::SETTINGS_KEY_FOLLOW_MAX_REDIRECT] = intval($extConf['client_max_redirect']);
    }
    if ($extConf['client_proxy_typo3'] === "1" && $GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_host'] !== "") {
      $this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS] = $GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_host'];
      if ($GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_port'] !== "") {
        $this -> settings[self::SETTINGS_KEY_PROXY_PORT] = intval($GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_port']);
      }
      if ($GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_user'] !== "") {
        $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS] = $GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_user'];
        if ($GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_password'] !== "") {
          $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS] .= ":".$GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_password'];
        }
        if ($GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_auth_scheme'] !== "") {
          $this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME] = $GLOBALS['TYPO3_CONF_VARS']['HTTP']['proxy_auth_scheme'];
        }
      }
    }
    elseif ($extConf['client_proxy_address'] !== "") {
      $this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS] = $extConf['client_proxy_address'];
      if ($extConf['client_proxy_port'] !== "") {
        $this -> settings[self::SETTINGS_KEY_PROXY_PORT] = intval($extConf['client_proxy_port']);
      }
      if ($extConf['client_proxy_credentials'] !== "") {
        $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS] = $extConf['client_proxy_credentials'];
        if ($extConf['client_proxy_auth_scheme'] !== "") {
          $this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME] = $extConf['client_proxy_auth_scheme'];
        }
      }
    }
    if ($extConf['client_user_agent'] !== "") {
      $this -> settings[self::SETTINGS_KEY_USER_AGENT] = $extConf['user_agent'];
    }
  }

  /**
   * Error handler
   *
   * @param int $code A value representation of an error
   * @param string  $message  A text description of an error
   * @return void
   */
  protected function errorHandler($code, $message) {

    if (isset($this -> settings[self::SETTINGS_KEY_LOG])) {
      $logMessage = 'Message: \''.$message.'\' Code= '.$code;
      switch($this -> settings[self::SETTINGS_KEY_LOG]) {
        case(self::LOGGER_SEVERITY_INFO):        
          $this -> logger -> info($logMessage);
          break;
        case(self::LOGGER_SEVERITY_WARNING):
          $this -> logger -> warning($logMessage);
          break;
        case(self::LOGGER_SEVERITY_ERROR):
          $this -> logger -> error($logMessage);
          break;
        case(self::LOGGER_SEVERITY_CRITICAL):
          $this -> logger -> critical($logMessage);
          break;
        default:
        //
      }
    }

    if ($this -> settings[self::SETTINGS_KEY_EXCEPTION] === true) {
      throw new \TS\Restclient\Client\HttpClientException($message, $code);
    }
    //Reuse HttpClient without reset it: unset possible previous error, it will be the last
    if (isset($this -> error)) {//Only to release
      $this -> error = null;
    }
    $this -> error = GeneralUtility::makeInstance('TS\Restclient\Client\HttpClientError', $code, $message);
  }

  /**
   * Setter for throw a HttpClientException in case of error
   *
   * @param bool $errorThrowException true or false
   * @return HttpClient The current instance
   */
  public function setErrorThrowException($errorThrowException = true) {
    $this -> settings[self::SETTINGS_KEY_EXCEPTION] = (bool) $errorThrowException;
    return $this;
  }

  /**
   * Setter for logger in case of error
   *
   * @param int $errorLog The values: 1 info, 2 warning, 3 error, 4 critical (use HttpClient::HTTP_SEVERITY_*)
   * @return HttpClient The current instance
   */
  public function setErrorLog($errorLog = 1) {
    if (isset($errorLog)) {      
      $this -> settings[self::SETTINGS_KEY_LOG] = intval($errorLog);
    }
    return $this;
  }

  /**
   * Setter for connection timeout
   *
   * @param int $connectionTimeout The connection timeout
   * @return HttpClient The current instance
   */
  public function setConnectionTimeout($connectionTimeout) {
    if (isset($connectionTimeout)) {
      $this -> settings[self::SETTINGS_KEY_CONNECTION_TIMEOUT] = intval($connectionTimeout);
    }
    return $this;
  }

  /**
   * Setter for timeout
   *
   * @param int $timeout The timeout
   * @return HttpClient The current instance
   */
  public function setTimeout($timeout) {
    if (isset($timeout)) {
      $this -> settings[self::SETTINGS_KEY_TIMEOUT] = intval($timeout);
    }
    return $this;
  }

  /**
   * Setter for follow redirect
   *
   * @param bool $followRedirect If true the client follow the redirect
   * @param int $maxRedirect The maximum number of redirect
   * @return HttpClient The current instance
   */
  public function setFollowRedirect($followRedirect, $maxRedirect = null) {
    $this -> settings[self::SETTINGS_KEY_FOLLOW_REDIRECT] = (bool) $followRedirect;
    if (isset($maxRedirect)) {
      $this -> settings[self::SETTINGS_KEY_FOLLOW_MAX_REDIRECT] = intval($maxRedirect);
    }
    return $this;
  }

  /**
   * Setter for proxy address, proxy port, and proxy credentials
   *
   * @param string $proxyAddress The proxy address
   * @param int $proxyPort The proxy port
   * @param string $proxyCredentials The proxy credentials
   * @param string $proxyAuthScheme The proxy authentication scheme
   * @return HttpClient The current instance
   */
  public function setProxy($proxyAddress, $proxyPort = null, $proxyCredentials = null, $proxyAuthScheme = null){
    //set
    if (isset($proxyAddress)) {
      //reset
      $this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS] = null;
      $this -> settings[self::SETTINGS_KEY_PROXY_PORT] = null;
      $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS] = null;
      $this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME] = null;
      //set
      $this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS] = $proxyAddress;
      if (isset($proxyPort)) {
        $this -> settings[self::SETTINGS_KEY_PROXY_PORT] = intval($proxyPort);
      }
      if (isset($proxyCredentials)) {
        $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS] = $proxyCredentials;
        if (isset($proxyAuthScheme)) {
          $this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME] = $proxyAuthScheme;
        }
      }
    }
    return $this;
  }

  /**
   * Setter for ssl check disabled
   *
   * @return HttpClient The current instance
   */
  public function setSslCheckDisabled() {
    $this -> settings[self::SETTINGS_KEY_SSL_CHECK_DISABLED] = true;
    return $this;
  }

  /**
   * Setter for ssl certificate
   *
   * @param string $certificateFilePath The certificate file path
   * @return HttpClient The current instance
   */
  public function setSslCertificate($certificateFilePath) {
    $this -> settings[self::SETTINGS_KEY_SSL_CERTIFICATE_PATH] = $certificateFilePath;
    return $this;
  }

  /**
   * Setter for cookie
   *
   * @param string $cookieFilePath The cookie file path
   * @return HttpClient The current instance
   */
  public function setCookie($cookieFilePath) {
    $this -> settings[self::SETTINGS_KEY_COOKIE_FILE_PATH] = $cookieFilePath;
    return $this;
  }

  /**
   * Setter for check httpcode
   *
   * @param bool $checkHttpCode If true the client check the response http status code
   * @return HttpClient The current instance
   */
  public function setCheckHttpCode($checkHttpCode = true) {
    $this -> settings[self::SETTINGS_KEY_CHECK_HTTPCODE] = (bool) $checkHttpCode;
    return $this;
  }

  /**
   * Setter for User Agent
   *
   * @param string $userAgent The user agent
   * @return HttpClient The current instance
   */
  public function setUserAgent($userAgent) {
    if (isset($userAgent)) {
      $this -> settings[self::SETTINGS_KEY_USER_AGENT] = $userAgent;
    }
    return $this;
  }

  /**
   * Get the request
   *
   * @return HttpClientRequest
   */
  public function getRequest() {
    return $this -> request;
  }

  /**
   * Set the request
   *
   * @param HttpClientRequest The request
   * @return HttpClient The current instance
   */
  public function setRequest(HttpClientRequest $request) {
    $this -> request = $request;
    return $this;
  }

  /**
   * Do request using current request
   *
   * @return mixed The response, false if an error occurs
   */
  public function exec() {
    if (!isset($this -> request)) {
      return false;
    }
    $method = $this -> request -> getMethod();
    $url = $this -> request -> getUrl();
    $header = $this -> request -> getHeader();
    $data = $this -> request -> getData();
    $files = $this -> request -> getFiles();
    return $this -> doRequest($method, $url, $header, $data, $files);
  }

  /**
   * Do request to an url using CRUD methods
   *
   * @param string $method The values get, post, put or delete
   * @param string $url The address to send the request
   * @param array $header The header eg. array('Content-Type: application/json', ...)
   * @param array|string $data The data eg. array('name' => 'john', 'surname' => 'doe', ...), {"name":"john","surname":"doe"}, ...
   * @param array $files The files eg. array('file1'=> array('filename'=> 'file1.txt', 'realpath'=> '/path/to/file1', 'mimetype'=>'txt'), 'file2'=> array('filename'=> 'file2.txt', 'realpath'=> '/path/to/file2', 'mimetype'=>'txt'), ...)
   * @return mixed The response, false if an error occurs
   */
  public function doRequest($method, $url, $header = null, $data = null, $files = null) {

    //Instance cURL
    if (isset($this -> client) && $this -> client !== false) {
      curl_close($this -> client);
    }
    $this -> client = curl_init();
    if ($this -> client === false) {
      $this -> errorHandler(self::ERROR_CODE_INSTANCE, __METHOD__ . ' cannot create an instance of client.');
      return false;
    }

    //Settings cURL
    $clientOpt = array();
    switch($method) {
      case stristr($method, self::REQUEST_METHOD_GET):
        $clientOpt[CURLOPT_HTTPGET] = 1;
        break;
      case stristr($method, self::REQUEST_METHOD_POST):
        $clientOpt[CURLOPT_POST] = 1;
        break;
      case stristr($method, self::REQUEST_METHOD_PUT):
        $clientOpt[CURLOPT_CUSTOMREQUEST] = 'PUT';
        break;
      case stristr($method, self::REQUEST_METHOD_DELETE):
        $clientOpt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        break;
      default:
        $this -> errorHandler(self::ERROR_CODE_METHOD, __METHOD__ . ' method \''.$method.'\' not suppoted.');
        return false;
    }
    if (isset($url)) {
      $clientOpt[CURLOPT_URL] = $url;
    }
    if (isset($header)) {
      $clientOpt[CURLOPT_HTTPHEADER] = $header;
    }
    if (isset($files)) {
      foreach($files as $fileKey => $fileData) {
        if (isset($fileData['realpath'])) {
          $postData[$fileKey] = '@'.$fileData['realpath'];
          if (isset($fileData['filename']) && $fileData['filename'] !=='') {
            $postData[$fileKey] .= ';filename='.$fileData['filename'];
          }
          if (isset($fileData['mimetype']) && $fileData['mimetype'] !=='') {
            $postData[$fileKey] .= ';type='.$fileData['mimetype'];
          }
        }
      }
    }
    if (isset($data)) {
       $postData = isset($postData) ? array_merge($data, $postData) : $data;//array, string
    }
    if (isset($postData)) {
      $clientOpt[CURLOPT_POSTFIELDS] = $postData;
    }

    $clientOpt[CURLOPT_RETURNTRANSFER] = true;//force
    $clientOpt[CURLOPT_HEADER] = true;//force
    if (isset($this -> settings[self::SETTINGS_KEY_CONNECTION_TIMEOUT])) {
      $clientOpt[CURLOPT_CONNECTTIMEOUT] = $this -> settings[self::SETTINGS_KEY_CONNECTION_TIMEOUT];
    }
    if (isset($this -> settings[self::SETTINGS_KEY_TIMEOUT])) {
      $clientOpt[CURLOPT_TIMEOUT] = $this -> settings[self::SETTINGS_KEY_TIMEOUT];
    }
    if (isset($this -> settings[self::SETTINGS_KEY_FOLLOW_REDIRECT]) && $this -> settings[self::SETTINGS_KEY_FOLLOW_REDIRECT] === true) {
      $clientOpt[CURLOPT_FOLLOWLOCATION] = true;
      if (isset($this -> settings[self::SETTINGS_KEY_FOLLOW_MAX_REDIRECT])) {
        $clientOpt[CURLOPT_MAXREDIRS] = $this -> settings[self::SETTINGS_KEY_FOLLOW_MAX_REDIRECT];
      }
    }
    if (isset($this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS])) {
      $clientOpt[CURLOPT_PROXY] = $this -> settings[self::SETTINGS_KEY_PROXY_ADDRESS];
      if (isset($this -> settings[self::SETTINGS_KEY_PROXY_PORT])) {
        $clientOpt[CURLOPT_PROXYPORT] = $this -> settings[self::SETTINGS_KEY_PROXY_PORT];
      }
      if (isset($this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS])) {
        $clientOpt[CURLOPT_PROXYUSERPWD] = $this -> settings[self::SETTINGS_KEY_PROXY_CREDENTIALS];
        if (isset($this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME])) {
          if ($this -> settings[self::SETTINGS_KEY_PROXY_AUTH_SCHEME] == 'digest') {
            $clientOpt[CURLOPT_HTTPAUTH] = CURLAUTH_DIGEST;
          }
          else {
            $clientOpt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
          }
        }
      }
    }
    if (isset($this -> settings[self::SETTINGS_KEY_SSL_CHECK_DISABLED]) && $this -> settings[self::SETTINGS_KEY_SSL_CHECK_DISABLED] === true) {
      $clientOpt[CURLOPT_SSL_VERIFYPEER] = false;
      $clientOpt[CURLOPT_SSL_VERIFYHOST] = false;
    }
    else {
      $clientOpt[CURLOPT_SSL_VERIFYPEER] = true;
      $clientOpt[CURLOPT_SSL_VERIFYHOST] = 2;
      if (isset($this -> settings[self::SETTINGS_KEY_SSL_CERTIFICATE_PATH])) {
        $clientOpt[CURLOPT_CAINFO] = $this -> settings[self::SETTINGS_KEY_SSL_CERTIFICATE_PATH];
      }
    }
    if (isset($this -> settings[self::SETTINGS_KEY_COOKIE_FILE_PATH])) {
      $clientOpt[CURLOPT_COOKIEJAR] = $this -> settings[self::SETTINGS_KEY_COOKIE_FILE_PATH];
      $clientOpt[CURLOPT_COOKIEFILE] = $this -> settings[self::SETTINGS_KEY_COOKIE_FILE_PATH];
    }
    if (isset($this -> settings[self::SETTINGS_KEY_USER_AGENT])) {
      $clientOpt[CURLOPT_USERAGENT] = $this -> settings[self::SETTINGS_KEY_USER_AGENT];
    }

    $setClient = curl_setopt_array($this -> client, $clientOpt);
    if ($setClient === false) {
      $this -> errorHandler(self::ERROR_CODE_OPTIONS, __METHOD__ . ' cannot set an options of client, cURL internal error = '.curl_error($this -> client));
      return false;
    }

    //Execute cURL
    $curlResponse = curl_exec($this -> client);

    $httpCode = curl_getinfo($this -> client, CURLINFO_HTTP_CODE);    
    if (isset($this -> settings[self::SETTINGS_KEY_CHECK_HTTPCODE]) && $this -> settings[self::SETTINGS_KEY_CHECK_HTTPCODE] === true) {      
      if ($httpCode >= self::SETTINGS_KEY_CHECK_HTTPCODE_VALUE) {        
        $this -> errorHandler(self::ERROR_CODE_EXEC, __METHOD__ . ' exec request return http code = '.$httpCode);
        return false;
      }
    }

    //Prepare HttpClientResponse
    if ($curlResponse === false) {
      $this -> errorHandler(self::ERROR_CODE_EXEC, __METHOD__ . ' exec request return false, cURL internal error = '.curl_error($this -> client));
      return false;
    }

    $responseHeaderSize = curl_getinfo($this -> client, CURLINFO_HEADER_SIZE);

    $response = array(
      self::RESPONSE_KEY_HEADER => substr($curlResponse, 0, $responseHeaderSize),
      self::RESPONSE_KEY_BODY => substr($curlResponse, $responseHeaderSize),
      self::RESPONSE_KEY_REQUEST_SIZE => curl_getinfo($this -> client, CURLINFO_REQUEST_SIZE),
      self::RESPONSE_KEY_CONTENT_TYPE => curl_getinfo($this -> client, CURLINFO_CONTENT_TYPE),
      self::RESPONSE_KEY_HTTP_CODE => $httpCode,
      self::RESPONSE_KEY_TOTAL_TIME => curl_getinfo($this -> client, CURLINFO_TOTAL_TIME),
      self::RESPONSE_KEY_CONNECT_TIME => curl_getinfo($this -> client, CURLINFO_CONNECT_TIME),
      self::RESPONSE_KEY_PRIMARY_IP => curl_getinfo($this -> client, 1048608),
      self::RESPONSE_KEY_PRIMARY_PORT => curl_getinfo($this -> client, 2097192)
    );

    if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/restclient/Classes/HttpClient.php']['responsePreProcess'])) {
      foreach($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/restclient/Classes/HttpClient.php']['responsePreProcess'] as $hookClassResponsePreProcess) {        
        $hookObjectResponsePreProcess = GeneralUtility::makeInstance($hookClassResponsePreProcess);
        $hookObjectResponsePreProcess -> responsePreProcess($response, $this);
      }
    }

    //Reuse HttpClient without reset it: unset possible previous response, it will be the last
    if (isset($this -> response)) {//Only to release
      $this -> response = null;
    }
    $this -> response = GeneralUtility::makeInstance('TS\Restclient\Client\HttpClientResponse', 
      $response[self::RESPONSE_KEY_HEADER],
      $response[self::RESPONSE_KEY_BODY],
      $response[self::RESPONSE_KEY_REQUEST_SIZE],
      $response[self::RESPONSE_KEY_CONTENT_TYPE],
      $response[self::RESPONSE_KEY_HTTP_CODE],
      $response[self::RESPONSE_KEY_TOTAL_TIME],
      $response[self::RESPONSE_KEY_CONNECT_TIME],
      $response[self::RESPONSE_KEY_PRIMARY_IP],
      $response[self::RESPONSE_KEY_PRIMARY_PORT]
    );

    return $this -> response;
  }

  /**
   * A shorthand for get request
   *
   * @param string $url The address to send the request
   * @param array $header The header eg. array('X-Powered-By: John Doe', ...)
   * @return mixed The response, false if an error occurs
   */
  public function get($url, $header = null) {
    $this -> doRequest(self::REQUEST_METHOD_GET, $url, $header);
    $response = isset($this -> response) && $this -> response !== false ? $this -> response -> getBody() : false;
    return $response;
  }

  /**
   * A shorthand for post request
   *
   * @param string $url The address to send the request
   * @param array $header The header eg. array('Content-Type: application/json', ...)
   * @param array|string $data The data eg. array('name' => 'john', 'surname' => 'doe'), {"name":"john","surname":"doe"}, ...
   * @return mixed The response, false if an error occurs
   */
  public function post($url, $header = null, $data = null) {
    return $this -> doRequest(self::REQUEST_METHOD_POST, $url, $header, $data);
  }

  /**
   * A shorthand for put request
   *
   * @param string $url The address to send the request
   * @param array $header The header eg. array('Content-Type: application/json', ...)
   * @param array|string $data The data eg. array('name' => 'john', 'surname' => 'doe'), {"name":"john","surname":"doe"}, ...
   * @return mixed The response, false if an error occurs
   */
  public function put($url, $header = null, $data = null) {
    return $this -> doRequest(self::REQUEST_METHOD_PUT, $url, $header, $data);
  }

  /**
   * A shorthand for delete request
   *
   * @param string $url The address to send the request
   * @param array $header The header eg. array('Content-Type: application/json', ...)
   * @param array|string $data The data eg. array('name' => 'john', 'surname' => 'doe'), {"name":"john","surname":"doe"}, ...
   * @return mixed The response, false if an error occurs
   */
  public function delete($url, $header = null, $data = null) {
    return $this -> doRequest(self::REQUEST_METHOD_DELETE, $url, $header, $data);
  }

  /**
   * Get the error
   *
   * @return HttpClientError The current error
   */
  public function getError() {
    return $this -> error;
  }

  /**
   * Get the response
   *
   * @return HttpClientResponse The current response
   */
  public function getResponse() {
    return $this -> response;
  }

  /**
   * Destructor
   *
   * @return void
   */
  public function __destruct() {
    if ($this -> client) {
      curl_close($this -> client);
    }
  }

}

?>
