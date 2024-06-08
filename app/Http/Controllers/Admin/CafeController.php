<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Cafe\UpdateRequest;
use App\Models\Cafe;
use App\Other\Images\InteractsWithImages;
use App\Http\Controllers\Core\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CafeController extends Controller
{
    use InteractsWithImages;
    public function showCafe(): View
    {
        $cafe = Cafe::get();
        $data = compact('cafe');

        return view('admin.main.cafe', $data);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $attributes = $request->getModelAttributes();

        if ($request->image !== null) {
            $attributes['image'] = $this
                ->createImagePipeline('logo')
                ->createFile($request->image, true)
                ->name;
        }

        Cafe::get()->update($attributes);

        return $this->back()->with($this->success('Профиль кафе был успешно обновлен'));
    }
}
