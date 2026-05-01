<?php

namespace App\Commands;

use Exception;
use HtmlShot\Font;
use HtmlShot\HtmlShot as HtmlShotRenderer;
use Illuminate\View\Factory as ViewFactory;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use TightenCo\Jigsaw\Console\ConsoleOutput;
use TightenCo\Jigsaw\Container;
use TightenCo\Jigsaw\File\Filesystem;
use TightenCo\Jigsaw\Providers\CollectionServiceProvider;
use TightenCo\Jigsaw\Providers\FilesystemServiceProvider;
use TightenCo\Jigsaw\Providers\MarkdownServiceProvider;
use TightenCo\Jigsaw\Providers\ViewServiceProvider;

class HtmlShot extends Command
{
    protected $name = 'shot';

    protected $description = 'Generate a picture from a self-contained html, svg, or blade file';

    protected function getArguments()
    {
        return [
            ['file', InputArgument::REQUIRED, 'Path to the html, svg, or blade file'],
            ['output', InputArgument::OPTIONAL, 'Output image path (defaults to input file with .png extension)'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Overwrite the output image if it exists'],
        ];
    }

    public function handle()
    {
        $app = $this->bootstrapJigsawApp();

        $file = $this->argument('file');
        $output = $this->argument('output');
        $overwrite = $this->option('force') ?? false;

        if (!is_file($file)) {
            $this->error("File not found: {$file}");
            return Command::FAILURE;
        }

        if (empty($output)) {
            $output = preg_replace('/\.(blade\.php|svg|html)$/i', '.png', $file);
        }

        if (is_file($output) && !$overwrite) {
            $this->warn("Output already exists: {$output}. Use --force to overwrite.");
            return Command::FAILURE;
        }

        /** @var Filesystem $filesystem */
        $filesystem = $app->make('files');

        /** @var ViewFactory $view */
        $view = $app->make('view');

        $html = match(true) {
            str_ends_with($file, '.blade.php') => $view->file($file)->render(),
            str_ends_with($file, '.svg') => $this->wrapSvgInHtml($filesystem->get($file)),
            default => $filesystem->get($file),
        };

        $this->line("File:   {$file}");
        $this->line("Output: {$output}");

        $png = $this->renderHtmlToImage($html, $filesystem);

        $filesystem->putWithDirectories($output, $png);

        $this->info("Done.");

        return Command::SUCCESS;
    }

    private function wrapSvgInHtml(string $svg): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { display: flex; align-items: center; justify-content: center; width: 1200px; height: 628px; overflow: hidden; }
                svg { width:100%;max-width: 100%; max-height: 100%; }
            </style>
        </head>
        <body>{$svg}</body>
        </html>
        HTML;
    }

    private function renderHtmlToImage(string $html, Filesystem $filesystem): string
    {
        $manifest = $filesystem->json('source/assets/build/manifest.json');

        if ($manifest === false || blank($manifest['source/_assets/css/main.css'] ?? null)) {
            throw new Exception("Manifest file not found, run npm run build before proceeding.");
        }

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
                $filesystem->get(__DIR__.'/../../source/assets/build/' . $manifest['source/_assets/css/main.css']['file']),
            ],
        ]);
    }

    protected function bootstrapJigsawApp(): Container
    {
        $app = new Container;

        $config = collect();

        $files = array_filter([
            './config.php',
            './helpers.php',
        ], 'file_exists');

        foreach ($files as $path) {
            $config = $config->merge(require $path);
        }

        if ($collections = value($config->get('collections'))) {
            $config->put('collections', collect($collections)->flatMap(
                fn ($value, $key) => is_array($value) ? [$key => $value] : [$value => []],
            ));
        }

        $app->instance('buildPath', [
            'source' => './source',
            'views' => './source',
            'destination' => './build_local',
        ]);

        $app->instance('config', $config);

        $consoleOutput = new ConsoleOutput();
        $consoleOutput->setup(1);
        $app->singleton('consoleOutput', fn () => $consoleOutput);

        foreach ([
            FilesystemServiceProvider::class,
            MarkdownServiceProvider::class,
            ViewServiceProvider::class,
            CollectionServiceProvider::class,
        ] as $provider) {
            (new $provider($app))->register();
        }

        return $app;
    }
}
