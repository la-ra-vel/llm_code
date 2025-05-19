<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupModule extends Model
{
    use HasFactory;

    public static function getModulesArray()
    {
        $modules = GroupModule::select('id', 'module_name','module_page')->get();
        $modulesArr = [];
        foreach ($modules as $key => $value) {
            $modulesArr[] = [
                'id' => $value->id,
                'name' => $value->module_name,
                'module_page' => $value->module_page
            ];
        }
        return $modulesArr;
    }
}
