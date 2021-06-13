<?php

namespace App\Actions\ProductCategories;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveCategoryAction
{
    use AsAction;

    /**
     * Execute the action
     * 
     * @param  string  $name
     * @param  string  $description
     * @param  int  $id
     * @return \App\Models\ProductCategory|bool
     */
    public function handle(
        string $name,
        ?string $description = null,
        ?int $id = null
    ) {
        $productCategory = empty($id)
            ? new ProductCategory()
            : ProductCategory::findOrFail($id);

        $productCategory->name = $name;
        $productCategory->description = $description;

        return $productCategory->save() ? $productCategory : false;
    }

    /**
     * Use as controller
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\Response
     */
    public function asController(Request $request, ?int $id = null)
    {
        Validator::make(
                $request->only(['name', 'description']),
                ProductCategory::validationRules($id)
            )
            ->validateWithBag('SaveProductCategory');

        $saved = $this->handle(
            $request->name,
            $request->description,
            $id
        );

        if ($request->wantsJson()) {
            return response()->json($saved);
        }

        return redirect()->route('categories.index');
    }
}
