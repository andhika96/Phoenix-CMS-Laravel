<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class mvc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mvc {requestNameMVC}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command to automaticaly create MVC';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create Controller
        Artisan::call('make:controller Web/'.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").' --requests --resource');
        
        // Create View
        Artisan::call('make:view '.strtolower($this->argument("requestNameMVC")).'/'.strtolower($this->argument("requestNameMVC")));
        
        // Create Model
        Artisan::call('make:model '.strtolower($this->argument("requestNameMVC")).'/'.strtolower($this->argument("requestNameMVC")));

        $this->info('MVC Created! <created by Andhika Adhitia N>');
    }
}
