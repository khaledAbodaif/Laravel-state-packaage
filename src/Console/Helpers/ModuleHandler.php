<?php


namespace Khaleds\State\Console\Helpers;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

/**
 * Trait ModuleHandler
 * @package Modules\CustomGenerator\Console\Helpers
 *
 * this trait responsible for any module functions
 */
trait ModuleHandler
{

    /**
     * @param string $moduleName
     *
     * @return Void
     *
     * check if the module not exists create it
     */
    public function createModule(string $moduleName):void
    {
        if (!Module::has($moduleName)){
            Artisan::call('module:make ' . $moduleName);
            $this->info('Module Created Success');
            $this->createPage($moduleName);
        }

    }

    public function createPage(string $moduleName):void
    {

        $check = File::exists(module_path($moduleName));
        if($check){
            try {
                File::makeDirectory(module_path($moduleName) . '/Pages');
                File::put(module_path($moduleName) . '/Pages/.gitkeep', '');
            }catch (\Exception $e){}

        }
    }
}
