<?php

use Illuminate\Support\Facades\Storage;

if(!function_exists('fileStorageManager')) {
    function fileStorageManager($file, $type, $model, $modelId, $oldFilePath = null) {
        $type = $type.'_path';

        $fileName = $file->getClientOriginalName();

        $typePersianName = translateTypeToPersian($type);
        $modelStoragePath = getModelStoragePath($model);

        $path = "$modelStoragePath/$modelId/$type/$fileName";

        if($model->where($type.'_path', $path)->exists() && !$oldFilePath) {
            return redirect()->back([
                'message' => "$typePersianName با این نام قبلا ثبت شده است"
            ]);
        } elseif ($oldFilePath != $path) {
            Storage::delete($oldFilePath);
            Storage::put($path);

            return $path;
        }

        Storage::put($path);

        $object = $model::find($modelId)->first();
        $object->$type = $path;
        $object->save();
    }

    function translateTypeToPersian($type) {
        $persianTypes = ['thumbnail' => 'تامبنیل', 'file' => 'فایل', 'pdf' => 'پی دی اف'];

        foreach($persianTypes as $key => $value) {
            if($type == $key) {
                return $type = $value;
                break;
            }
        }
    }

    function getModelStoragePath($model) {
        $storagePaths = ['App\Models\ServiceCenter' => 'service-centers'];

        foreach ($storagePaths as $key => $value) {
            return $path = $value;
            break;
        }
    }
}