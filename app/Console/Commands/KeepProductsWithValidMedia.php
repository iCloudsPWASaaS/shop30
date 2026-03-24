<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class KeepProductsWithValidMedia extends Command
{
    protected $signature = 'products:keep-valid {--dry-run : Preview without deleting}';
    protected $description = 'Keep 1000 products with existing media, delete the rest';

    public function handle()
    {
        $this->info('Scanning products...');

        $keepIds   = [];
        $deleteIds = [];

        Product::withoutGlobalScopes()->with('media')->chunkById(200, function ($products) use (&$keepIds, &$deleteIds) {
            foreach ($products as $product) {
                // Use correct collection name: 'product'
                $media = $product->getFirstMedia('product');

                if ($media && file_exists($media->getPath()) && count($keepIds) < 1000) {
                    $keepIds[] = $product->id;
                } else {
                    $deleteIds[] = $product->id;
                }
            }
        });

        $this->info('Products to KEEP : ' . count($keepIds));
        $this->warn('Products to DELETE: ' . count($deleteIds));

        if ($this->option('dry-run')) {
            $this->info('[Dry run] No deletions performed.');
            return;
        }

        if (!$this->confirm('Proceed with deletion?')) {
            $this->info('Aborted.');
            return;
        }

        $bar = $this->output->createProgressBar(count($deleteIds));
        $bar->start();

        foreach (array_chunk($deleteIds, 100) as $chunk) {
            Product::withoutGlobalScopes()->with('media')->whereIn('id', $chunk)->each(function ($product) use ($bar) {
                $product->forceDelete(); // bypass SoftDeletes — Spatie removes media files + directory
                $bar->advance();
            });
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done!');
    }
}