<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Status\DeleteRequest;
use App\Http\Requests\Admin\Status\UpdateRequest;
use App\Http\Requests\Admin\Status\CreateRequest;

use App\Models\Status;
use App\Http\Controllers\Core\Controller;

use Exception;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Throwable;

use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    public function showDelete(int $statusId): View
    {
        $status = Status::findOrFail($statusId);

        return view('admin.statuses.delete')
            ->with(compact('status'));
    }

    /**
     * @throws Exception
     */
    public function showStatuses(Request $request): View|JsonResponse
    {
        $statuses = Status::ordered('id')
            ->map(static function (Status $status) {
                return [
                    'status'     => $status,
                    'created_at' => formatDate($status->createdAt, true),
                    'updated_at' => formatDate($status->updatedAt, true),
                ];
            });

        if ($request->ajax()) {
            return DataTables::of($statuses)

                ->addIndexColumn()
                ->addColumn('action', static function (array $row) {
                    $id = $row['status']['id'];

                    $modalRoute = route('admin.statuses.delete.index', ['id' => $id]);
                    $editRoute  = route('admin.statuses.update.index', ['id' => $id]);

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

        return view('admin.statuses.index');
    }

    public function showCreate(): View
    {
        return view('admin.statuses.create');
    }

    public function showUpdate(int $statusId): View
    {
        $status = Status::find($statusId);

        return view('admin.statuses.update', compact('status'));
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        return $this->unsupported();
//        $status = Status::find($request->statusId);
//
//        if ($status === null) {
//            return $this->back()->with($this->internal());
//        }
//
//        $status->name = $request->name;
//        $status->save();
//
//        return $this->back()->with($this->success('Статус успешно изменен'));
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        return $this->unsupported();
//        try {
//            $status = new Status();
//            $status->name = $request->name;
//            $status->save();
//
//        } catch (Throwable) {
//            return $this->back()->withErrors($this->internal());
//        }
//
//        return $this->back()->with($this->success('Статус успешно создан'));
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->internalServerErrorResponse();
//        try {
//            Status::findOrFail($request->statusId)?->delete();
//        } catch (Throwable) {
//            return $this->internalServerErrorResponse();
//        }
//
//        return $this->ok();
    }
}
