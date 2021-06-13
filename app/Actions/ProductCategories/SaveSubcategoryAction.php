<?php

namespace App\Actions\ProductCategories;

use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveSubcategoryAction
{
    use AsAction;

    /**
     * Execute the action
     * 
     * @param  ProductCategory  $productCategory,
     * @param  string  $name
     * @param  string  $description
     * @param  int  $id
     * @return \App\Models\ProductSubcategory|bool
     */
    public function handle(
        ProductCategory $category,
        string $name,
        ?string $description = null,
        ?int $id = null
    ) {
        $subcategory = empty($id)
            ? new ProductSubcategory(['category_id' => $category->id])
            : $category->subcategories()->findOrFail($id);

        $subcategory->category_id = $category->id;
        $subcategory->name = $name;
        $subcategory->description = $description;

        return $subcategory->save() ? $subcategory : false;
    }

    /**
     * Use as controller
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @param  \App\Models\ProductSubcategory  $productSubcategory
     * @return \Illuminate\Http\Response
     */
    public function asController(
        Request $request,
        ProductCategory $category,
        ?ProductSubcategory $subcategory
    ) {
        Validator::make(
                $request->only(['name', 'description']),
                ProductSubcategory::validationRules($subcategory->id ?? null)
            )
            ->validateWithBag('SaveProductSubcategory');

        $saved = $this->handle(
            $category,
            $request->name,
            $request->description,
            $subcategory->id ?? null
        );

        if ($request->wantsJson()) {
            return response()->json($saved);
        }

        return redirect()->route('categories.show', compact('category'));
    }
}
