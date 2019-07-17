<?php

return [
    'router' => [
        'path'  => routes_path(),
        'files' => ['routes'],

        'default-web-handler' => 'HomeController@home',
    ]
];