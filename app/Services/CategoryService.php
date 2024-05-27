<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * @return Category[]
     */
    public function getAllChildren(int|string $id): array
    {
        /**
         * @var Category[] $categories
         */
        $categories = Category::all()->all();
        $result = [];

        $findChildren = static function (int|string $id) use ($categories, &$findChildren, &$result) {
            foreach ($categories as $category) {
                if ($category->parentId === $id) {
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

    /**
     * @param int|string $categoryId
     * @return Category|null
     */

    public function acquireById(int|string $categoryId): ?Category
    {
        return Category::find($categoryId);
    }
}
