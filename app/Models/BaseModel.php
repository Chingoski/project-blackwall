<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class BaseModel extends Model
{
    use SoftDeletes;

    public function getTransformer(?string $transformerClassName = null): TransformerAbstract|null
    {
        $modelClass = Str::afterLast(get_class($this), '\\');

        $transformerClassName ??= "App\Transformers\\{$modelClass}Transformer";

        if (class_exists($transformerClassName)) {
            return new $transformerClassName();
        }

        return null;
    }
}
