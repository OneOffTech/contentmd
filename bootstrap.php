<?php

use App\Listeners\GeneratePostMarkdownVariant;
use App\Listeners\GenerateSitemap;
use App\Listeners\GenerateSocialImages;
use TightenCo\Jigsaw\Jigsaw;

/** @var \Illuminate\Container\Container $container */
/** @var \TightenCo\Jigsaw\Events\EventBus $events */

/*
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 */

// Markdown variants of blog posts for LLM use 
$events->afterBuild([
    GeneratePostMarkdownVariant::class,
    GenerateSocialImages::class,
    GenerateSitemap::class,
]);
