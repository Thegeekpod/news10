<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate XML sitemap for search engines';

    /**
     * Execute the console command.
     */
    public function handle(SitemapService $sitemapService): int
    {
        $this->info('Generating sitemap.xml...');

        $result = $sitemapService->generateFile();

        $this->info('Sitemap generated successfully!');
        $this->table(
            ['File Path', 'Size', 'URLs Included', 'Generated At'],
            [
                [$result['file_path'], number_format($result['file_size'] / 1024, 2).' KB', $result['url_count'], $result['generated_at']],
            ]
        );

        return Command::SUCCESS;
    }
}
