<?php


namespace Khaleds\State\Services;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Khaleds\State\Services\Helpers\CanManipulateFiles;

class StateGenerator
{

    use CanManipulateFiles;

    private string $abstractName;
    private string $stateNames="";
    private string $stateClassValue="";
    private array $stateNameSpaces;

    public function __construct(
        public ?string $moduleName,
        public string $stateAbstractName,
        public string $stateClasses,
        public string $path,
        public string $defaultState

    )
    {
        $this->generateModelName();
    }

    /**
     *
     * convert table name (table name with s and _) to model camelCase
     */
    public function generateModelName(): void
    {
        $this->abstractName=ucfirst(Str::camel($this->stateAbstractName.'Abstract'));
        $states=explode(',',$this->stateClasses);
        foreach ($states as $state){
            $this->stateNameSpaces[ucfirst(Str::camel($state))]="";
            $this->stateNames .="'".$state."'"."=>".ucfirst(Str::camel($state))."State::class,";
            $this->stateClassValue .=ucfirst(Str::camel($state))."State::class,";
            if (empty($this->moduleName))
                $this->stateNameSpaces[ucfirst(Str::camel($state))]='use App\Status\\'.$this->stateAbstractName.'\\'.ucfirst(Str::camel($state)).'state'.';';
//            else
//                $this->stateNameSpaces[ucfirst(Str::camel($state))]='use Module\\'.$this->moduleName.'\\'.$this->stateAbstractName.'\Status'.'\\'.ucfirst(Str::camel($state)).'state'.';';

        }
    }

    public function render()
    {

        $fullPath=$this->path . '/Status/' . $this->stateAbstractName;
        if (File::exists($this->path. '/Status')) {
            try {
                File::makeDirectory($fullPath);
            } catch (\Exception $e) {
                dd('first',$e->getMessage());
            }
        }else{
            try {
                File::makeDirectory($this->path . '/Status');
                File::makeDirectory($fullPath);
            } catch (\Exception $e) {

            }
        }

        if (empty($this->moduleName))
            $replacements['nameSpace']="App\Status\\".$this->stateAbstractName;

        else
        $replacements['nameSpace']="Modules\\".$this->moduleName."\Status\\".$this->stateAbstractName;

        $replacements["className"]=$this->stateAbstractName;
        $replacements["stateClasses"]=implode($this->stateNameSpaces);
        $replacements["stateClassKeyValue"]=(string)$this->stateNames;
        $replacements["stateClassValue"]=(string)$this->stateClassValue;
        $replacements["defaultState"]=(string)$this->defaultState."State::class";


        $this->copyStubToApp('AbstractClass',$fullPath.'/'.$this->stateAbstractName.'Abstract.php',$replacements);

        $this->renderState($fullPath);


    }

    public function renderState($fullPath){

        if (empty($this->moduleName)){
            $replacements['nameSpace']="App\Status\\".$this->stateAbstractName;
            $replacements['abstractNameSpace']="App\Status\\".$this->stateAbstractName;

        }
        else{
            $replacements['nameSpace']="Modules\\".$this->moduleName."\Status\\".$this->stateAbstractName;
            $replacements['abstractNameSpace']="Modules\\".$this->moduleName."\Status\\".$this->stateAbstractName;
        }


        $replacements['abstractName']=$this->stateAbstractName;

        $states=explode(',',$this->stateClasses);

        foreach ($states as $state){
            $replacements['name']=$state;
            $this->copyStubToApp('StateClass',$fullPath.'/'.$state.'State.php',$replacements);

        }

    }
}
