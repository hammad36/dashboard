<?php

return [

    'template' => [
        'templateHeaderStart.php'        =>  TEMPLATE_PATH . 'templateHeaderStart.php',
        'templateHeaderEnd.php'          =>  TEMPLATE_PATH . 'templateHeaderEnd.php',
        'sidebar'                        =>  TEMPLATE_PATH . 'sidebar.php',
        'navbar'                         =>  TEMPLATE_PATH . 'navbar.php',
        ':view'                          =>   ':action_view',
        'footer'                         =>  TEMPLATE_PATH . 'footer.php',
        'templateEnd'                    =>  TEMPLATE_PATH . 'templateEnd.php'
    ],

    'header_resources' => [
        'css' => [
            'style'         => CSS . 'style.css',
            'productStyles' => CSS . 'productStyles.css',
            'invoiceStyles' => CSS . 'invoiceStyles.css'
        ]
    ],

    'footer_resources' => [
        'js' => [
            'index'         => JS . 'index.js',
            'product'       => JS . 'product.js'
        ]
    ]

];
