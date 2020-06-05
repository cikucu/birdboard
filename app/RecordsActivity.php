<?php

namespace App;

trait RecordsActivity
{
    

    public $oldAttributes = [];
   
    /**
     * Boot the trait
     */
    public static function bootRecordsActivity()
    {

      foreach (self::recordableEvents() as $event ) {

          static::$event(function ($model) use ($event){

             $model->recordActivity($model->activityDescription($event));

          });

         if($event === 'updated'){

            static::updating(function($model){

               $model->oldAttributes = $model->getOriginal();

            });
         }
      
       }
    }

    protected function activityDescription ($description)
    {

      // if(class_basename($this) !== 'Project'){

         return "{$description}_" . strtolower(class_basename($this));
      // }

      // return $description; 

    }

    protected static function recordableEvents()
    {

      if(isset(static::$recordableEvents)){

         return static::$recordableEvents;
      }

      return ['created', 'updated'];
      
    }


    public function recordActivity($description)
    {
       $this->activity()->create([
           'user_id' =>  ($this->project ?? $this)->owner->id,
           'description' => $description,
           'changes' => $this->activityChanges(),
           'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
       ]);
    }

   

    public function activity()
     {
        return $this->morphMany(Activity::class, 'subject')->latest();
     }

    public function activityChanges()
    {
       if($this->wasChanged()){
        return [
           'before' => array_diff($this->oldAttributes, $this->getAttributes()),
           'after' => $this->getChanges()
        ];
       }
    }
}
