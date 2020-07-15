<?php

namespace App\Providers;

trait RecordActivity
{
    public $old = [];

    public static function bootRecordActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity(
                    $model->activityDescription($event)
                );
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->old = $model->getOriginal();
                });
            }
        }
    }

    public function activityDescription($description)
    {
        $description = "{$description} ".strtolower(class_basename($this));

        return $description;
    }

    protected static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated'];
    }

    public function recordActivity($description)
    {
        $this->activities()->create([
            'user_id'     => $this->activityOwner(),
            'description' => $description,
            'changes'     => $this->activityChanges(),
            'project_id'  => class_basename($this) === 'Project' ? $this->id : $this->project->id,
        ]);
    }

    protected function activityOwner()
    {
        return ($this->project ?? $this)->owner_id;
    }

    protected function activityChanges()
    {

        // if ($description === "updated project"){
        if ($this->wasChanged()) {
            return $this->getWhatChanges();
        }
    }

    protected function getWhatChanges()
    {
        return [
            'before' => array_diff($this->old, $this->getAttributes()),
            // 'after' => array_diff($this->getAttributes(), $this->old)
            'after' => array_except($this->getChanges(), 'updated_at'),
        ];
    }
}
