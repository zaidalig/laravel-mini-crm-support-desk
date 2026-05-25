<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Boot the trait and register Eloquent event listeners.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('Created', $model);
        });

        static::updated(function ($model) {
            static::logActivity('Updated', $model);
        });

        static::deleted(function ($model) {
            static::logActivity('Deleted', $model);
        });
    }

    /**
     * Log the model activity to the database.
     */
    protected static function logActivity(string $action, $model)
    {
        $module = class_basename($model);
        
        // Retrieve a user-friendly name from the model attributes
        $name = $model->name ?? $model->title ?? "ID #{$model->id}";
        
        $description = "{$action} {$module} \"{$name}\"";

        ActivityLog::create([
            'action' => $action,
            'module' => Str::plural($module),
            'description' => $description,
        ]);
    }
}
