<?php
namespace Khaleds\State\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Khaleds\State\Services\StateGenerator;

//use Modules\Generator\Console\Helpers\ModuleHandler;

class GeneratorCommand extends Command
{

//    use ModuleHandler;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'state:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generates state files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // state name
        $moduleName= $this->ask('Please input your module name? ex:Status or empty to use app directory');
        $stateAbstractName= $this->ask('Please input your state abstract name? ex:PaymentStatus');
        $stateClasses= $this->ask('Please input state classes as CSV ? ex:Paid,Pending,Cancel');
        $defaultStateClasses= $this->ask('Please input default state  ? ex:Pending');

        $path=base_path();

        if (!empty($moduleName))
            $path=  base_path().'/Modules/'.$moduleName;

        $stateService = new StateGenerator($moduleName,$stateAbstractName,$stateClasses,$path,$defaultStateClasses);
        $stateService->render();
    }
}
