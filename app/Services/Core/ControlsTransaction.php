<?php

namespace App\Services\Core;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

trait ControlsTransaction
{
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    protected function rollbackTransaction(): void
    {
        DB::rollBack();
    }

    protected function commitTransaction(): void
    {
        DB::commit();
    }

    /**
     * @param Closure(): void $callback
     * @param int $attempts
     * @param null|Closure(Throwable): void $onFail
     * @return void
     * @throws QueryException
     */

    protected function transaction(
        Closure  $callback,
        int      $attempts = 1,
        ?Closure $onFail = null,
    ): void
    {
        if ($onFail !== null) {
            try {
                DB::transaction($callback, $attempts);
            } catch (Throwable $t) {
                $onFail($t);
            }
        } else {
            DB::transaction($callback, $attempts);
        }
    }
}
