<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ModelFetcherController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->only(['models']);

        $validator = Validator::make($data, [
            'models' => 'required|array|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json();
        }

        $records = [];
        foreach ($data['models'] as $modelSnakeCase => $modelId) {
            $records[$modelSnakeCase] = null;
            $modelCamelCase = Str::camel(Str::singular($modelSnakeCase));
            $className = "\\App\\Models\\{$modelCamelCase}";
            if (class_exists($className)) {
                $whereMethod = is_array($modelId) && count($modelId) > 1
                    ? 'whereIn'
                    : 'where';

                $findMethod = is_array($modelId) && count($modelId) > 1
                    ? 'get'
                    : 'first';

                $methodArg = is_array($modelId)
                    ? (count($modelId) == 1 ? (int) array_shift($modelId) : $modelId)
                    : (int) $modelId;

                $records[$modelSnakeCase] = (new $className)->$whereMethod('id', $methodArg)->$findMethod();
            }
        }

        return response()->json($records);
    }
}
