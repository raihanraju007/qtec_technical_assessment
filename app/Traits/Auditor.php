<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait Auditor
{
    protected static function boot(): void
    {
        parent::boot();

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? 1;
            $model->updated_at = Carbon::now();
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? 1;
            $model->created_at = Carbon::now();
        });
    }
}
