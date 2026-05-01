<?php

namespace App\Listeners;

use App\Models\BlogPost;
use App\Models\Guideline;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;
use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\PageVariable;

/**
 * Generate markdown variants for pages that have a markdown template block
 */
class GeneratePostMarkdownVariant
{
    /**
     * afterBuild we extract the markdown from the rendered page and write it to dedicated files for agent consumption
     */
    public function handle(Jigsaw $jigsaw)
    {
        $baseUrl = $jigsaw->getConfig('baseUrl');

        if (! $baseUrl) {
            echo("\nTo generate markdown files, please specify a 'baseUrl' in config.php.\n\n");

            return;
        }

        $jigsaw->getPages()->reject(function($page, $path){
            return Str::contains($path, [
                '.', // entries with extension are usually images, fonts or javascript 
                'hot' // Vite hot reload
            ]); // check for collections
        })
        ->each(function (PageVariable $page, $path) use ($jigsaw) {
            
            $html = $jigsaw->readOutputFile(ltrim($page->_meta->path, '/'). '/index.html');

            $crawler = new Crawler($html);

            // Get the Markdown template element if present and extract
            // to a dedicated page based on the filename
            try {
                $attributes = trim($crawler->filter('#markdown-template')
                    ->text(normalizeWhitespace: false));
    
                $jigsaw->writeOutputFile(ltrim($page->_meta->path, '/'). '/index.md', $attributes);
                $jigsaw->writeOutputFile(ltrim($page->_meta->relativePath, '/') . '/' . $page->_meta->filename. '.md', $attributes);

            } catch (InvalidArgumentException $th) {
                
                // When the page does not have a markdown output we skip it
                dump("skipping {$page->_meta->path}");
            }

        });

    }
}