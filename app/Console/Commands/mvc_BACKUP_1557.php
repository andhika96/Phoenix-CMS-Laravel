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

<<<<<<< HEAD
    protected $type = 'Action';

=======
>>>>>>> a049bff3284202b8af299fb700a70f19923ab5cc
    /**
     * Execute the console command.
     */
    public function handle()
    {
<<<<<<< HEAD
        // Create Model
        Artisan::call('make:model '.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").' -ms');

        // Create Controller
        // Artisan::call('make:controller Web/'.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").'Controller --model='.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").' --resource --no-interaction');
        
        // Create View
        Artisan::call('make:view '.strtolower($this->argument("requestNameMVC")).'/'.strtolower($this->argument("requestNameMVC")));
    
        // Create service
        Artisan::call('make:action '.$this->argument("requestNameMVC").'Service --m='.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").'');

        // Create custom controller
        Artisan::call('make:custom-controller Web/'.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").'Controller --m='.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").' --s='.$this->argument("requestNameMVC").'');
=======
        // Create Controller
        Artisan::call('make:controller Web/'.$this->argument("requestNameMVC").'/'.$this->argument("requestNameMVC").' --requests --resource');
        
        // Create View
        Artisan::call('make:view '.strtolower($this->argument("requestNameMVC")).'/'.strtolower($this->argument("requestNameMVC")));
        
        // Create Model
        Artisan::call('make:model '.strtolower($this->argument("requestNameMVC")).'/'.strtolower($this->argument("requestNameMVC")));
>>>>>>> a049bff3284202b8af299fb700a70f19923ab5cc

        $this->info('MVC Created! <created by Andhika Adhitia N>');
    }
}
