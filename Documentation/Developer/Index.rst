.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Developer's manual
============

Suppose, for instance, an Extbase controller action:

.. code-block:: php
   
   $httpClientRequest = $this->objectManager->get('TS\Restclient\Client\HttpClientRequest')->setMethod('GET')->setUrl('http://domain/api/users/123/profile');
   $httpClient = $this->objectManager->get('TS\Restclient\Client\HttpClient')->setErrorThrowException(true)->setRequest($httpClientRequest);
   try {     
     $httpClient->exec();
     
     $httpClientResponse = $httpClient->getResponse();
     $userProfile = $httpClientResponse->getBody();    
   }
   catch (HttpClientException $e) {
     $errorCode = $e->getCode();
     $errorMEssage = $e->getMessage();     
   }   

You can use HttpClient method doRequest setting the request in-line:

.. code-block:: php

   $httpClient = $this->objectManager->get('TS\Restclient\Client\HttpClient')->setErrorThrowException(true);   
   try {     
     $httpClient->doRequest('GET', 'http://domain/api/users/123/profile');
     
     $httpClientResponse = $httpClient->getResponse();
     $userProfile = $httpClientResponse->getBody();  
   }
   catch (HttpClientException $e) {
     $errorCode = $e->getCode();
     $errorMEssage = $e->getMessage();
   }
   
   
Also you can use the CRUD shorthands:

.. code-block:: php

    try {
      $userProfile = $this->objectManager->get('TS\Restclient\Client\HttpClient')->setErrorThrowException(true)->get('http://domain/api/users/123/profile');
    }
    catch (HttpClientException $e) {
      $errorCode = $e->getCode();
      $errorMEssage = $e->getMessage();
    }

If you need to set the HTTP header and to send data/files with the request:

.. code-block:: php

    $httpClientRequest = $this->objectManager->get('TS\Restclient\Client\HttpClientRequest') 
      -> setMethod('post') 
      -> setUrl('http://domain/api/users/123/feed')
      -> setHeader(array('Authorization: OAuth 1234567890'))
      -> setFields(array('message' => 'It\'s just a message sent with RESTclient.'))
      -> setFiles array('file1'=> array('filename'=> 'file1.txt', 'realpath'=> '/path/to/file1', 'mimetype'=>'txt'));
    $httpClient = $this->objectManager->get('TS\Restclient\Client\HttpClient')->setErrorThrowException(true)->setRequest($httpClientRequest);
    try {     
      $httpClient->exec();
      
      $httpClientResponse = $httpClient->getResponse();
      $userProfile = $httpClientResponse->getBody();
    }
    catch (HttpClientException $e) {
      $errorCode = $e->getCode();
      $errorMEssage = $e->getMessage();
    }
    
If you don't set errorThrowException, you can analyze the HttpClientError: 

.. code-block:: php

    $httpClientRequest = $this -> objectManager -> get('TS\Restclient\Client\HttpClientRequest')-> setMethod('get')->setUrl('http://domain/api/users/123/profile') 
    $httpClient = $this->objectManager->get('TS\Restclient\Client\HttpClient')->setRequest($httpClientRequest);
    $httpClientResponse = $httpClient->exec()->getResponse();
    if ($httpClientResponse === false) {
      $errorCode = $httpClient->getError()->getCode();
      $errorMessage = $httpClient->getError()->getMessage();
    }


API Reference
^^^^^^^^^^^^^^^^^^^^^

This table provides the description of HttpClient* classes.

+-------------------------------------------------------------+-------------------------------------------------+
| Class Summary                                               | Description                                     |
+=============================================================+=================================================+
| \\TS\\Restclient\\Client\\HttpClient                        | The class that you must use to do the request   |
|                                                             | and to receive the response.                    |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| \\TS\\Restclient\\Client\\HttpClientRequest                 | The request.                                    |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| \\TS\\Restclient\\Client\\HttpClientResponse                | The response of HttpClient request.             |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| \\TS\\Restclient\\Client\\HttpClientError                   | If you don't set throwErrorException, in case of|
|                                                             | failure you still can get an instance of        |
|                                                             | HttpClientError from HttpClient.                |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| \\TS\\Restclient\\Client\\HttpClientException               | The Exception class thrown from HttpClient      |
|                                                             | in case of failure.                             |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+

This table provides how to use the methods provided by HttpClient. 

+--------------------------------------------------------------------------------------------------------------------------------------------------+
| Method summary                                                                                                                                   |
+==================================================================================================================================================+
| setErrorThrowException (bool $errorThrowException = true)                                                                                        | 
|   If set to TRUE, the HttpClient will throw an HttpClientException in case of failure.                                                           |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setErrorLog (int $errorLog = 1)                                                                                                                  | 
|   If set then the HttpClient will write a log to file typo3temp/logs/typo3.log in case of failure.                                               |
|                                                                                                                                                  |   
|   The values thant you can use to set the log severity level:                                                                                    |                                                                                                                                                   
|                                                                                                                                                  | 
|   HttpClient::LOGGER_SEVERITY_INFO, HttpClient::LOGGER_SEVERITY_WARNING, HttpClient::LOGGER_SEVERITY_ERROR, HttpClient::LOGGER_SEVERITY_CRITICAL.|
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setConnectionTimeout (int $connectionTimeout)                                                                                                    | 
|   Set the max seconds allowed to connect to the server.                                                                                          |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setTimeout (int $timeout)                                                                                                                        | 
|   Set the max seconds allowed to execute a request to the server.                                                                                |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setFollowRedirect (bool $followRedirect[, int $maxRedirect = null])                                                                              | 
|   If set to TRUE, the client will follow the redirect. You can also set the maximum number of redirects.                                         |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setProxy (string $proxyAddress[, int $proxyPort][, string $proxyCredentials][, string $proxyAuthScheme = null)                                   | 
|   Set the proxy parameters to send the request.                                                                                                  | 
|                                                                                                                                                  | 
|   The credentials 'username:password'.                                                                                                           | 
|                                                                                                                                                  | 
|   The authentication scheme are 'basic' or 'digest'.                                                                                             | 
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setSslCheckDisabled (void)                                                                                                                       | 
|   To accept any server(peer) certificate.                                                                                                        |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setSslCertificate (string $certificateFilePath)                                                                                                  | 
|   If you don't set sslCheckDisable, you must set the path of certificate that client should trust.                                               |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setCookie (string $cookieFilePath)                                                                                                               | 
|   Set the filename that containg the cookie data and where to save it.                                                                           |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setCheckHttpCode (bool $checkHttpCode = true)                                                                                                    | 
|   If set to TRUE the client check the response http status code (failure if greater or equal than 400).                                          |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setUserAgent (string $userAgent)                                                                                                                 | 
|   Set the user-agent header.                                                                                                                     |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getRequest (void)                                                                                                                                | 
|   Get the current request.                                                                                                                       |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setRequest (HttpClientRequest $request)                                                                                                          | 
|   Set the request.                                                                                                                               |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| exec (void)                                                                                                                                      | 
|   Execute the current request.                                                                                                                   |
|                                                                                                                                                  | 
|   Returns the HttpClient current instance, or FALSE if an error occurs.                                                                          | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| doRequest (string $method, string $url[, array $header = null][, array $fields = null][, array $files = null])                                   |
|   Execute a CRUD request.                                                                                                                        |  
|                                                                                                                                                  |                                                                          
|   The methods are: 'get', 'post', 'put', 'delete'.                                                                                               |
|                                                                                                                                                  |  
|   The url is address to send the request.                                                                                                        | 
|                                                                                                                                                  |        
|   The header is something like array('X-Powered-By: John Doe', ...).                                                                             |
|                                                                                                                                                  |   
|   The fields is something like array('name' => 'john', 'surname' => 'doe', ...).                                                                 |
|                                                                                                                                                  | 
|   The files is something like array('file1'=> array('filename'=> 'file1.txt', 'realpath'=> '/path/to/file1', 'mimetype'=>'txt'), ...).           |
|                                                                                                                                                  |
|   Returns the HttpClient current instance, or FALSE if an error occurs.                                                                          | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| get (string $url[, bool $jsonDecode = null])                                                                                                     | 
|   The shorthand for a GET request.                                                                                                               | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| post (string $url[, array $fields = null])                                                                                                       | 
|   The shorthand for a POST request.                                                                                                              | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| put (string $url[, array $fields = null])                                                                                                        | 
|   The shorthand for a PUT request.                                                                                                               | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| delete (string $url[, array $fields = null])                                                                                                     | 
|   The shorthand for a DELETE request.                                                                                                            | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getError (void)                                                                                                                                  | 
|   Returns the HttpClientError.                                                                                                                   | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getResponse (void)                                                                                                                               | 
|   Returns the HttpClientResponse.                                                                                                                | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| reset ([bool $initClient = true])                                                                                                                | 
|   Use if you want reset the state of HttpClient instance (it resets last error, settings, request and response).                                 | 
|                                                                                                                                                  | 
|   If you set initClient to TRUE, then it will keep the basic configuration provided from Backend module "Extension Manager".                     | 
|                                                                                                                                                  | 
|   Returns the HttpClient current instance.                                                                                                       | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+

This table provides how to use the methods provided by HttpClientRequest.

+--------------------------------------------------------------------------------------------------------------------------------------------------+
| Method summary                                                                                                                                   |
+==================================================================================================================================================+
| getMethod (void)                                                                                                                                 | 
|   Returns the method 'get', 'post', 'put', or 'delete'.                                                                                          |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setMethod (string $method)                                                                                                                       | 
|   Set the method.                                                                                                                                | 
|                                                                                                                                                  |                       
|   Returns the HttpClientReuqest current instance.                                                                                                |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getUrl (void)                                                                                                                                    |  
|   Returns the url.                                                                                                                               |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setUrl (string $url)                                                                                                                             | 
|   Set the url.                                                                                                                                   | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getHeader (void)                                                                                                                                 |  
|   Returns the header.                                                                                                                            |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setHeader (array $header)                                                                                                                        | 
|   Set the header, something like array('X-Powered-By: John Doe', ...).                                                                           | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| addHeaders (array $headers)                                                                                                                      |  
|   Add headers to HttpClientRequest header, something like array('X-Cache: 0', ...).                                                              | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| resetHeader (void)                                                                                                                               |  
|   Reset the current header.                                                                                                                      | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getFields (void)                                                                                                                                 |  
|   Returns the fields.                                                                                                                            |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setFields (array $fields)                                                                                                                        |  
|   Set the fields, something like array('name' => 'john', 'surname' => 'doe', ...).                                                               | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| addFields (array $fields)                                                                                                                        |  
|   Add fields to HttpClientRequest fields, something like array('age' => '23', ...).                                                              | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| resetFields (void)                                                                                                                               |  
|   Reset the current fields.                                                                                                                      | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| getFiles (void)                                                                                                                                  |  
|   Returns the files.                                                                                                                             |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| setFiles (array $files)                                                                                                                          |  
|   Set the files.                                                                                                                                 | 
|                                                                                                                                                  |  
|   Something like array('file1'=> array('filename'=> 'file1.txt', 'realpath'=> '/path/to/file1', 'mimetype'=>'txt'), ...).                        | 
|                                                                                                                                                  |  
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| addFiles (array $files)                                                                                                                          |  
|   Add files to HttpClientRequest files.                                                                                                          | 
|                                                                                                                                                  |   
|   Something like array('file2'=> array('filename'=> 'file2.txt', 'realpath'=> '/path/to/file2', 'mimetype'=>'txt'), ...).                        | 
|                                                                                                                                                  | 
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  | 
+--------------------------------------------------------------------------------------------------------------------------------------------------+
| resetFiles (void)                                                                                                                                |  
|   Reset the current files.                                                                                                                       | 
|                                                                                                                                                  |   
|   Returns the HttpClientRequest current instance.                                                                                                | 
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
|                                                                                                                                                  |  
+--------------------------------------------------------------------------------------------------------------------------------------------------+


This table provides how to use the methods provided by HttpClientResponse.

+---------------------------------------------------------------------------------------+
| Method summary                                                                        |
+=======================================================================================+
| getHeader ([bool $toArrary = false])                                                  | 
|   Returns the response header.                                                        | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getBody ([bool $jsonDecode = false])                                                  | 
|   Returns the response body.                                                          | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getRequestSize (void)                                                                 | 
|   Returns the size of request sent.                                                   | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getContentType (void)                                                                 | 
|   Returns the Content Type of request sent.                                           | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getHttpCode (void)                                                                    | 
|   Returns the last HTTP code received.                                                | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getConnectTime (void)                                                                 |
|   Returns the seconds to establish the connection.                                    |  
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getPrimaryIp (void)                                                                   | 
|   Returns the IP address of the last connection.                                      | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getPrimaryPort (void)                                                                 | 
|   Returns the destination port of the last connection.                                | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+


This table provides how to use the methods provided by HttpClientError.

+---------------------------------------------------------------------------------------+
| Method summary                                                                        |
+=======================================================================================+
| getCode (void)                                                                        | 
|   Returns the numerical value of the error message.                                   | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getMessage (void)                                                                     | 
|   Returns the text of the error message.                                              |                                                                                         
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+


This table provides how to use the methods provided by HttpClientException.

+---------------------------------------------------------------------------------------+
| Method summary                                                                        |
+=======================================================================================+
| getCode (void)                                                                        | 
|   Returns the numerical value of the error message.                                   | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+
| getMessage (void)                                                                     | 
|   Returns the text of the error message.                                              |                                                                                         
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
|                                                                                       | 
+---------------------------------------------------------------------------------------+

This table provides the description of the error codes.

+-------------------------------------------------------------+-------------------------------------------------+
| Code                                                        | Description                                     |
+=============================================================+=================================================+
| -2                                                          | An error occurs when creating an instance       |
|                                                             | of the client.                                  |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| -4                                                          | An error occurs when setting an option.         |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| -8                                                          | It has been requested unsupported method.       |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+
| -16                                                         | An error occurs when execute the request.       |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
|                                                             |                                                 |
+-------------------------------------------------------------+-------------------------------------------------+

Hook 
^^^^^^^^^^^^^^^^^^^^^
Before the HttpClient returns the response to you, you can use this hook to process it:

Add in your ext_localconf.php:

.. code-block:: php


  $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/restclient/Classes/HttpClient.php']['responsePreProcess'][] = '\Namespace\Foo';


In your extkey/Classes/Foo.php:

.. code-block:: php

  namespace \Namespace;

  class Foo {
    
    public function responsePreProcess(&$response, $pObj) {
      //process $response
    }
    
  }
