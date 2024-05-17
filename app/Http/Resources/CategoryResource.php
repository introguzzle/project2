<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Utils\Auth;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * @var Category $resource
     */
    public $resource;
    public function __construct(Category $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        return $this->resource->toArray();
    }
}
