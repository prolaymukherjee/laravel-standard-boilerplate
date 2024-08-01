<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    protected function executeInTransaction(callable $callback)
    {
        return DB::transaction(function () use ($callback) {
            try {
                return $callback();
            } catch (Exception $e) {
                DB::rollBack();
                //need write log
                throw $e;
            }
        });
    }
}
