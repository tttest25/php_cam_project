<?php

/**
 * Used to store website configuration information.
 *
 * @var string or null
 */
function config($key = '')
{
    $config = [
        'name' => 'Camera',
        'site_url' => 'https://wiki.gorodperm.ru/www/cam',
        'pretty_uri' => false,
        'nav_menu' => [
            '' => 'Home',
            'list' => 'List',
            'video' => 'Video',
            /*'period' => 'Period',*/
            
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'version' => 'v3.0',
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
