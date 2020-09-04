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
imports:
    - { resource: parameters.yml }

services:
# (...)

    secure_headers_response_listener:
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


    html2pdf_converter_listener:
        class: Psuw\CommonListener\HttpKernel\EventListener\ConvertResponseListener
        arguments:
            - 'event.getResponse().headers.get("Content-Type", "text/html")  == "text/html" && event.getRequest().getRequestFormat() == "pdf"'
            - '@dompdf'
            - 
                - 'event.getResponse().headers.set("Content-Type","application/pdf")'
                - 'converter.loadHtml(content)'
                - 'converter.render()'
                - 'converter.output()'
        tags:
            - { name: kernel.event_listener, event: kernel.response }
    dompdf:
        class: Dompdf\Dompdf
        public: false
        arguments: ['%dompdf.options%']
```
