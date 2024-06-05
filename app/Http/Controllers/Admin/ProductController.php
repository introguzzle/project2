<?php

namespace App\Http\Controllers\Admin;

use App\DTO\ImageDTO;

use App\Http\Requests\Admin\Product\DeleteRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Requests\Admin\Product\CreateRequest;

use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Core\Controller;

use App\Services\ProductService;

use Exception;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Throwable;

use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    private ProductService $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function showDelete(int $productId): View
    {
        $product = Product::findOrFail($productId);
        $data = compact('product');

        return view('admin.products.delete')->with($data);
    }

    /**
     * @throws Exception
     */
    public function showProducts(Request $request): View|JsonResponse
    {
        $products = Product::ordered('id')
            ->map(function(Product $product) {
                return [
                    'product'    => $product,
                    'category'   => $product->category->name,
                    'image'      => $product->getPath(),
                    'created_at' => formatDate($product->createdAt, true),
                    'updated_at' => formatDate($product->updatedAt, true),
                ];
            });

        if (!$request->ajax()) {
            return view('admin.products.index');
        }

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('action', static function (array $row) {
                $id = $row['product']['id'];

                $modalRoute = route('admin.products.delete.index', ['id' => $id]);
                $editRoute = route('admin.products.update.index', ['id' => $id]);

                return '<a href="' . $editRoute . '"
                            data-id="' . $id . '"
                            class="complete btn btn-success btn-sm"
                            style="width: 120px">Редактировать
                   </a>
                    <button type="button"
                            onclick="confirmAndOpenDialog(\'' . $modalRoute . '\', \'' . $id . '\')"
                            data-id="' . $id . '"
                            class="btn btn-outline-info btn-sm"
                            style="width: 120px">Удалить
                   </button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function showCreate(): View
    {
        $categories = Category::query()->orderBy('id')->get();

        $data = compact('categories');
        return view('admin.products.create', $data);
    }

    public function showUpdate(int $productId): View
    {
        $product = Product::findOrFail($productId);
        $categories = Category::query()->orderBy('id')->get();

        $data = compact('categories', 'product');
        return view('admin.products.update', $data);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $imageDTO = new ImageDTO(
            $request->image,
            $request->main,
            $request->imageName,
            $request->imageDescription
        );

        $this->productService->update(
            $request->productId,
            $request->getModelAttributes(),
            $imageDTO
        );

        return redirect()->back()->with($this->success('Продукт был успешно изменен'));
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        $imageDTO = new ImageDTO(
            $request->image,
            $request->main,
            $request->imageName,
            $request->imageDescription
        );

        try {
            $this->productService->create(
                $request->getModelAttributes(),
                $imageDTO
            );
        } catch (Throwable) {
            return redirect()->back()->withErrors($this->internal());
        }

        return redirect()->back()->with($this->success('Продукт был успешно создан'));
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        try {
            Product::findOrFail($request->productId)?->delete();
        } catch (Throwable) {
            return $this->internalServerErrorResponse();
        }

        return $this->ok();
    }
}
