<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * @return Category[]
     */

    public function acquireAllCategories(): array
    {
        return Category::all()->all();
    }

    public function acquireCategoryName(
        int|string $categoryId
    ): string
    {
        return Category::query()
            ->where('id', '=', $categoryId)
            ->first()
            ->getAttribute('name');
    }

    /**
     * @return Category[]
     */
    public function acquireAllChildren(int|string $id): array
    {
        $categories = Category::all()->all();
        $result = [];

        $findChildren = function (int|string $id)
        use ($categories, &$findChildren, &$result) {
            foreach ($categories as $category) {
                if ($category->parent_id == $id) {
                    $result[] = $category;
                    $findChildren($category->id);
                }
            }
        };

        $findChildren($id);

        return $result;
    }

    /**
     * @return Category[]
     */

    public function acquireChain(int|string $id): array
    {
        $categories = Category::all()->all();
        $result = [];

        $findParent = function (int|string $id)
        use ($categories, &$findParent, &$result) {
            foreach ($categories as $category) {
                if ($category->id == $id) {
                    $result[] = $category;
                    if ($category->parent_id) {
                        $findParent($category->parent_id);
                    }
                }
            }
        };

        $findParent($id);

        return $result;
    }
}
