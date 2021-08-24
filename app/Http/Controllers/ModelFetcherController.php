<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ModelFetcherController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        // To do: add middleware for adminstrators only
        $this->middleware(['auth:sanctum', 'frontend']);
    }

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
        foreach ($data['models'] as $modelSnakeCase => $modelRequest) {
            $modelId = is_array($modelRequest)
                ? $modelRequest['id']
                : $modelRequest;

            $records[$modelSnakeCase] = null;
            $modelCamelCase = Str::camel(Str::singular($modelSnakeCase));
            $className = "\\App\\Models\\{$modelCamelCase}";
            if (class_exists($className)) {
                $modelObj = new $className;

                $modelRelationships = collect();

                if (is_array($modelRequest) && isset($modelRequest['with'])) {
                    $modelRelationships = collect(explode(',', $modelRequest['with']))->map(
                        fn($relation) => trim($relation)
                    );
                }

                $fetchAll = strtolower($modelId) == 'all';
                $whereMethod = is_array($modelId) && count($modelId) > 1
                    ? 'whereIn'
                    : 'where';

                $findMethod = $fetchAll
                    || (is_array($modelId) && count($modelId) > 1)
                    ? 'get'
                    : 'first';

                $methodArg = is_array($modelId)
                    ? (count($modelId) == 1 ? (int) array_shift($modelId) : $modelId)
                    : (int) $modelId;

                $recordBuilder = $fetchAll
                    ? $modelObj
                    : $modelObj->$whereMethod('id', $methodArg);

                if (count($modelRelationships)) {
                    $recordBuilder->with($modelRelationships->toArray());
                }

                $records[$modelSnakeCase] = $recordBuilder->$findMethod();
            }
        }

        return response()->json($records);
    }
}
