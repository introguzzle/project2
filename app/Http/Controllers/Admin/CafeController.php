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
        $cafe = Cafe::all()->first();
        $data = compact('cafe');
        return view('admin.main.cafe', $data);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $attributes = $request->getModelAttributes();

        if ($request->image !== null) {
            $pipeline = $this->createImagePipeline('logo');
            $uploadedImage = $pipeline->createFile($request->image, true);
            $attributes['image'] = $uploadedImage->name;
        }

        Cafe::all()->first()->update($attributes);

        return back()->withInput()->with($this->success('Профиль кафе был успешно обновлен'));
    }
}
