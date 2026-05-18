<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            self::writeLog('created', $model);
        });

        static::updated(function ($model) {
            self::writeLog('updated', $model);
        });

        static::deleted(function ($model) {
            self::writeLog('deleted', $model);
        });
    }

    private static function writeLog(string $action, $model): void
    {
        if (! app()->runningInConsole() && auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => class_basename($model),
                'model_id' => $model->getKey(),
                'description' => ucfirst($action) . ' ' . class_basename($model) . ' #' . $model->getKey(),
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
