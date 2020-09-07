README
=======

This file is part of the PsuwCommonListener package.

```
@package PsuwCommonListener  
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
        class: Psuw\CommonListener\EventListener\ExpressionEvaluatingListener
        arguments: 
            - 
                headers:
                  X-Frame-Options: SAMEORIGIN
                  X-XSS-Protection: '1; mode=block'
                  X-Content-Type-Options: nosniff
                  Referrer-Policy: same-origin
        calls: [[addExpression, ["event.getResponse().headers.add(headers)"]]]
        tags: [{ name: kernel.event_listener, event: kernel.response, method: onEvent }]


    # HTML to CSV converter: allows tables download to use with spreadsheet.
    html2csv_converter:
        class: Psuw\CommonListener\HttpKernel\EventListener\ConvertResponseListener
        arguments:
            - 'event.getResponse().headers.get("Content-Type", "text/html")  == "text/html" && event.getRequest().getRequestFormat() == "csv"'
            - 'bariew\html2csv\Html2Csv'
            -
               - 'event.getResponse().headers.set("Content-Type", "text/csv")'
               - "converter.toFile(event.getRequest().server.get('REQUEST_TIME') ~ '.csv')"
        tags: [{ name: kernel.event_listener, event: kernel.response }]


    # CSRF Token Validation
    #
    # Logs the case and throws Access Denied Exception if token is not initialized
    # at session level, not present in reqest or not equal. For that purpose two
    # extra methods of expressions language needed to be registered (`hash_equals` 
    # and `json_encode`).
    csrf_validation:
        class: Psuw\CommonListener\HttpKernel\EventListener\ThrowIfListener
        calls:
            - [setExpressionLanguage, ['@expression_language']]
            - [setContext, [{name: '_csrf_token', logger: "@=service('logger').withName('security')"}]]
        arguments:
            - >
              event.getRequest().isMethod('POST') && 
              (event.getRequest().getSession().get(name) == null ||
              !event.getRequest().request.has(name) || 
              !hash_equals(event.getRequest().getSession().get(name), event.getRequest().request.get(name))) &&
              (logger.error('csrf invalid: expected ' ~ name ~ ': ' ~  event.getRequest().getSession().get(name) ~ ' in request ' ~ json_encode(event.getRequest().request.all())) || true)
        tags: [{ name: kernel.event_listener, event: kernel.request, method: onEvent, priority: 100}]
    expression_language:
        class: Symfony\Component\ExpressionLanguage\ExpressionLanguage
        calls:
            - [registerProvider, ['@expression_language_provider']]
    expression_language_provider:
        class: Psuw\CommonListener\Expression\FunctionExpressionLanguageProvider
        arguments: [['json_encode', 'hash_equals']]
```
