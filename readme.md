Welcome to &lt;dev/log&gt;
==========================
&lt;dev/log&gt; is a beautiful issue tracking application specifically made for individual developers and small teams. The focus here lies on simplicity and effectiveness. Implemented in PHP with HTML5 and JavaScript, it offers maximum flexibility and portability.

# Development
This section provides information on how to correctly build the application.

## Requirements
* nodejs / npm
* grunt
* bower
* composer
* sass / scss
* compass
* apache2 / php 5.3+

## Building
To correctly set up and build the application, run ``make`` in the root directory. After all dependencies have been successfully installed, you may use ``grunt build`` and ``grunt serve``.

## Test Deployment
Since the REST API is written in PHP, it cannot be served by the regular nodejs server that is used for testing the JavaScript client. To work around this issue, the application will access the API via a separate server that is listening on port 8088 by default. Check ``config-dev.js`` and ``config-dist.js`` for configuration.

## Actual Deployment
Run ``grunt build`` and copy the contents of ``devlog/dist`` onto your regular server.

## Do Note...
*More details will follow as this project evolves.*
