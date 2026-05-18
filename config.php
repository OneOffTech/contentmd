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

    'isActive' => function ($page, $path) {
        $pagePath = str_replace('\\', '/', trimPath($page->getPath()));
        $path = trimPath($path);

        if(Str::endsWith($path, '*')){
            $path = rtrim($path, '*');
            return Str::startsWith($pagePath, $path) || $pagePath === $path;
        }
        return Str::endsWith($pagePath, $path) || $pagePath === $path;
    },
];
