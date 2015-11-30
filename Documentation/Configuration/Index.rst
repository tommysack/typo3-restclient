.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Configuration
============

This extension provides a basic configuration from Backend module “Extension Manager”:

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         error_throw_exception

   Data type
         Boolean

   Description
         If TRUE the HttpClient throw an HttpClientException in case of failure

   Default
         FALSE

.. container:: table-row

   Property
         error_log

   Data type
         Integer

   Description
         The HttpClient write a log to file typo3temp/logs/typo3.log in case of failure (you can set the log severity level)                  

   Default
         Empty

.. container:: table-row

   Property
         client_check_httpcode

   Data type
         Boolean

   Description
         If TRUE the HttpClient check the response http status code (failure if greater or equal than 400)

   Default
         FALSE


.. container:: table-row

   Property
         client_connection_timeout

   Data type
         Integer

   Description
         Max seconds allowed to connection to server

   Default
        Empty 


.. container:: table-row

   Property
         client_timeout

   Data type
         Integer

   Description
         Max seconds allowed to individual requests

   Default
       Empty  

.. container:: table-row

   Property
         client_follow_redirect

   Data type
         Boolean

   Description
         The client will follow the redirect

   Default
       FALSE  

.. container:: table-row

   Property
         client_max_redirect

   Data type
         Integer

   Description
         Maximum number of redirect to follow

   Default
       Empty  

.. container:: table-row

   Property
         client_proxy_typo3

   Data type
         Boolean

   Description
         Use the HTTP proxy system settings (TYPO3_CONF_VARS[HTTP][proxy_*])

   Default
       Empty  



.. container:: table-row

   Property
         client_proxy_address

   Data type
         String

   Description
         The proxy address to send the requests

   Default
       Empty  
       

.. container:: table-row

   Property
         client_proxy_port

   Data type
         Integer

   Description
         The proxy port to use

   Default
       Empty  


.. container:: table-row

   Property
         client_proxy_credentials

   Data type
         String

   Description
         The proxy credentials to use

   Default
       Empty  

.. container:: table-row

   Property
         client_proxy_auth_scheme

   Data type
         String

   Description
         The authorization method to use: basic, digest.

   Default
       Empty

.. container:: table-row

   Property
         client_user_agent

   Data type
         String

   Description
         Use any User Agent you choose.

   Default
       Empty
              
.. ###### END~OF~TABLE ######
