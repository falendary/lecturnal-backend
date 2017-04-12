<?php
return [
//    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
//    'vendorPath' => $_SERVER["DOCUMENT_ROOT"] . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
