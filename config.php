<?php

use \Illuminate\Support\Str;

return [
    'production' => false,
    'baseUrl' => '',
    'title' => 'content-md - The web, spoken fluently to AI agents',
    'description' => 'content-md is an open specification for optimized content exchange. Serve high-fidelity textual representation to AI agents through standard HTTP content negotiation.',
    'github' => 'https://github.com/OneOffTech/contentmd',
    'collections' => [],

    // URL helper functions
    'url' => function ($page, $path, $absolute = true) {
        if(Str::startsWith($path, 'http')){
            return $path;
        }

        if($absolute){
            
            return  rtrim($page->baseUrl, '/') . '/' . trimPath($path);

        }

        return  '/' . trimPath($path);

    },
];
