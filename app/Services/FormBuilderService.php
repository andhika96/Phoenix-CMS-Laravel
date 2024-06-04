<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FormBuilderService
{
    /** 
     * Get all models in a given directory.
     *
     * @param string $directory
     * @return array
     */
    public function getModels($directory)
    {
        $models = [];

        foreach (File::allFiles($directory) as $file) {
            $namespace = $this->getNamespace($file->getPathname());
            $class = $namespace . '\\' . Str::before($file->getFilename(), '.php');

            if (is_subclass_of($class, 'Illuminate\Database\Eloquent\Model') && !(new \ReflectionClass($class))->isAbstract()) {
                $models[] = $class;
            }
        }

        return $models;
    }

    /**
     * Get the namespace of a given file.
     *
     * @param string $file
     * @return string
     */
    public function getNamespace($file)
    {
        $src = file_get_contents($file);

        if (preg_match('/namespace\s+(.+?);/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function store()
    {
        // {
        //     {formName}_{mode} : {
        //         model: 'app\Model\Something',
        //         schema: {
        //             fullname: {
        //                 type: text,
        //                 placeholder: 'Full Name',
        //                 label: 'Full Name',
        //             }
        //         }
        //     }
        // }


    }
}
