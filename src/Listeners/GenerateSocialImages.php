<?php

namespace App\Listeners;

use Exception;
use HtmlShot\Font;
use TightenCo\Jigsaw\Jigsaw;
use Illuminate\Container\Container;
use \Illuminate\Support\Str;
use TightenCo\Jigsaw\PageVariable;
use HtmlShot\HtmlShot as HtmlShotRenderer;
use Illuminate\View\Factory as ViewFactory;
use TightenCo\Jigsaw\File\Filesystem;
use TightenCo\Jigsaw\Parsers\FrontMatterParser;

class GenerateSocialImages
{
    /**
     * afterBuild we generate the open graph images for the pages that does not have them already
     */
    public function handle(Jigsaw $jigsaw)
    {
        $baseUrl = $jigsaw->getConfig('baseUrl');

        if (! $baseUrl) {
            echo("\nTo generate a open graph images, please specify a 'baseUrl' in config.php.\n\n");

            return;
        }

        $manifest = json_decode($jigsaw->readOutputFile('assets/build/manifest.json'), true);

        if ($manifest === false || blank($manifest['source/_assets/css/main.css'] ?? null)) {
            throw new Exception("Manifest file not found, run \"npm run build\" before proceeding.");
        }

        $stylesheet = $jigsaw->readOutputFile('assets/build/' . $manifest['source/_assets/css/main.css']['file']);


        $parser = $jigsaw->app->get(FrontMatterParser::class);

        /** @var ViewFactory $view */
        $view = $jigsaw->app->make('view');

        $jigsaw->getPages()->reject(function($page, $path){
            return Str::contains($path, [
                '.', // entries with extension are usually images, fonts or javascript 
                'hot' // Vite hot reload
            ]);
        })
        ->each(function (PageVariable $page, $path) use ($jigsaw, $parser, $stylesheet, $view) {
            
            $source = $jigsaw->readSourceFile("{$page->_meta->filename}.{$page->_meta->extension}");

            $frontmatter = $parser->getFrontMatter($source);

            if(!isset($frontmatter['card'])){
                return;
            }

            $image = $this->renderOpenGraph($view->make($frontmatter['card']['template'], $frontmatter)->render(), $stylesheet);

            $jigsaw->writeOutputFile($frontmatter['card']['path'], $image);

        });
    }

    private function renderOpenGraph(string $html, string $stylesheet): string
    {
        return HtmlShotRenderer::render($html, [
            'width' => 1200,
            'height' => 628,
            'format' => 'png',
            'fonts' => [
                Font::fromFile(__DIR__.'/../../source/assets/fonts/Inter-Regular.woff2', 'Inter', 400),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/Inter-Medium.woff2', 'Inter', 500),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/Inter-SemiBold.woff2', 'Inter', 600),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/Inter-Bold.woff2', 'Inter', 700),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/FiraMono-Regular.ttf', 'FiraMono', 400),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/FiraMono-Medium.ttf', 'FiraMono', 500),
                Font::fromFile(__DIR__.'/../../source/assets/fonts/FiraMono-Bold.ttf', 'FiraMono', 700),
            ],
            'stylesheets' => [
                $stylesheet,
            ],
        ]);
    }

}