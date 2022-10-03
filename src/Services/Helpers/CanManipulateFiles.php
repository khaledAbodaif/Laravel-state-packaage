<?php

namespace Khaleds\State\Services\Helpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait CanManipulateFiles
{
    protected function checkForCollision(): void
    {
        try{
            foreach ($this->folderDirectory as $folder) {
                File::makeDirectory(module_path($this->moduleName) .  $folder);
            }

            if (File::exists($this->targetPath))
                File::delete($this->targetPath);

        }catch (\Exception $e){}


    }

    protected function copyStubToApp(string $stub, string $targetPath, array $replacements = [], $customPath="/../../stubs/"): void
    {
        $filesystem = app(Filesystem::class);

        if($customPath === '/../../stubs/'){
            if (! $this->fileExists($stubPath = base_path("{$customPath}{$stub}.stub"))) {
                $stubPath = __DIR__ . "{$customPath}{$stub}.stub";
            }
        }
        else {
            if (! $this->fileExists($stubPath = "{$customPath}{$stub}.stub")) {
                $stubPath = "{$customPath}{$stub}.stub";
            }
        }


        $stub = Str::of($filesystem->get($stubPath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $stub = (string) $stub;

        $this->writeFile($targetPath, $stub);
    }

    protected function fileExists(string $path): bool
    {
        $filesystem = app(Filesystem::class);

        return $filesystem->exists($path);
    }

    protected function writeFile(string $path, string $contents): void
    {
        $filesystem = app(Filesystem::class);

        $filesystem->ensureDirectoryExists(
            (string) Str::of($path)
                ->beforeLast('/'),
        );

        $filesystem->put($path, $contents);
    }
}
