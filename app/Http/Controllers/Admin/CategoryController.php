<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Admin\Category\CreateRequest;
use App\Http\Requests\Admin\Category\DeleteRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * @throws Exception
     */
    public function showCategories(Request $request): View|JsonResponse
    {
        $categories = Category::ordered('id')
            ->map(static function (Category $category) {
                return [
                    'category'        => $category,
                    'parent'          => $category->parent?->name,
                    'products_count'  => $category->products->count(),
                    'products'        => $category->products,
                    'created_at'      => formatDate($category->createdAt, true),
                    'updated_at'      => formatDate($category->updatedAt, true),
                ];
            });

        if ($request->ajax()) {
            $table = DataTables::of($categories);
            $table->addIndexColumn();
            $table->addColumn('action', static function (array $row) {
                $id = $row['category']['id'];

                $updateRoute = route('admin.categories.update.index', ['id' => $id]);
                $deleteRoute = route('admin.categories.delete.index', ['id' => $id]);

                return '<a href="' . $updateRoute . '"
                        data-id="' . $id . '"
                        class="complete btn btn-success btn-sm"
                        style="width: 120px">Редактировать
               </a>
                <button type="button"
                        onclick="confirmAndOpenDialog(\'' . $deleteRoute . '\', \'' . $id . '\')"
                        data-id="' . $id . '"
                        class="btn btn-outline-info btn-sm"
                        style="width: 120px">Удалить
               </button>';
            });

            $table->rawColumns(['action'])
                ->addColumn('parent-link', static function (array $row) {
                    return route('admin.categories.update.index', ['id' => $row['category']['id']]);
                });

            return $table->toJson();
        }

        return view('admin.categories.index');
    }

    public function showUpdate(int $id): View
    {
        $category = Category::find($id);
        $categories = Category::ordered('id');

        $data = compact('category', 'categories');
        return view('admin.categories.update', $data);
    }

    public function showDelete(int $id): View
    {
        $category = Category::find($id);
        $data = compact('category');
        return view('admin.categories.delete', $data);
    }

    public function showCreate(): View
    {
        $categories = Category::ordered('id');
        $data = compact('categories');
        return view('admin.categories.create')->with($data);
    }

    public function delete(DeleteRequest $request): RedirectResponse
    {
        Category::find($request->categoryId)?->delete();
        return redirect()->route('admin.categories.index');
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        Category::find($request->categoryId)?->update([
            'name'      => $request->name,
            'parent_id' => $request->parentId
        ]);

        return $this
            ->back()
            ->with($this->success('Категория была успешно обновлена'));
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        $category = new Category();
        $category->name = $request->name;

        if (($parent = $request->parentId) !== null) {
            $category->parent()->associate(Category::find($parent));
        }

        $category->save();

        return $this
            ->back()
            ->with($this->success('Категория была успешно создана'));
    }
}
