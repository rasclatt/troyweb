<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AutoPatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:patch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $baseDir = __DIR__.'/recipes';
        foreach(scandir($baseDir) as $file) {
            $this->info("Checking {$baseDir}/{$file}...");
            if(stripos($file, 'patch-') !== false) {
                $recipes = json_decode(file_get_contents("{$baseDir}/{$file}"), true);
                if($recipes) {
                    $this->info('Running patch...');
                    foreach($recipes as $recipe) {
                        if($recipe['type'] === 'call') {
                            Artisan::call($recipe['command'], $recipe['args']);
                            if($recipe['description'])
                                $this->info("{$recipe['description']}...");
                            if(!empty($recipe['seed'])) {
                                Artisan::call('db:seed', ['--class' => $recipe['seed']]);
                                $this->info("Seeding {$recipe['seed']} complete...");
                            }
                        } elseif($recipe['type'] === 'sql') {
                            DB::statement($recipe['command']);
                            if($recipe['description'])
                                $this->info("{$recipe['description']}...");
                            else
                                $this->info("Query run...");
                        }
                    }
                    $patchFile = str_replace('patch-', 'patched-'.now(), $file);
                    rename("{$baseDir}/{$file}", "{$baseDir}/{$patchFile}");
                }
            }
        }
        Artisan::call('cache:clear');
        $this->info('Cache cleared...');
        return 0;
    }
}