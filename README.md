README
=======

This file is part of the PsuwExpressionEvaluator package.

```
@package PsuwExpressionEvaluator  
@copyright Copyright (c) 2019, Paweł Suwiński  
@author Paweł Suwiński <psuw@wp.pl>  
@license MIT  
```

Example of usage
-----------------

```
# config.yml 

services:
# (...)
    # Adds secure headers to every response. 
    secure_headers_response:
        class: Psuw\ExpressionEvaluator\Evaluator
        arguments: 
            - 'arg1.getResponse().headers.add(headers)'
            - 
                headers:
                  X-Frame-Options: SAMEORIGIN
                  X-XSS-Protection: '1; mode=block'
                  X-Content-Type-Options: nosniff
                  Referrer-Policy: same-origin
        tags: [{ name: kernel.event_listener, event: kernel.response, method: __invoke }]
```
