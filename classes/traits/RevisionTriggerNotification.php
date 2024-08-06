<?php namespace Yfktn\Yfktnutil\Classes\Traits;

use Exception;
use Log;

trait RevisionTriggerNotification
{
    /**
     * @var array revisionTriggerFields list of attributes to monitor for change and the handler to call.
     * As the handler you need to be an array of class string and later the static method name of it.
     * Ex:
     * protected $revisionTriggerFields = [
         'status' => [StatusRevisionHandler::class, 'runStatus'],
         'createdHandlerAction' => [StatusRevisionHandler::class, 'runCreated'],
         'deletedHandlerAction' => [StatusRevisionHandler::class, 'runDeleted'],
     ]
     * where createHandlerAction is reserved index and to be called when model created, also with the
     * deletedHandlerAction as to be called when model deleted.
     * Every calling method must be static with one parameter that would be the instance of current $model,
     * for updated then it would be 3 params: the current model, old value, new value.
     * 
     * For update field, you can add more than 1 field when you want to notify on update for each field.
     * Ex: 'status|message' then it means for status and message field on update then send notification.
     * 
     * protected $revisionTriggerFields = [];
     */

    public $revisionTriggerNotificationEnabled = true;

    public function initializeRevisionTriggerNotification()
    {
        if (!is_array($this->revisionTriggerFields)) {
            throw new Exception(sprintf(
                'The $revisionTriggerFields property in %s must be an array to use the trait.',
                get_class($this)
            ));
        }
        
        $this->bindEvent('model.afterCreate', function () {
            $this->revisionTriggerNotificationAfterCreate();
        });
        $this->bindEvent('model.afterUpdate', function () {
            $this->revisionTriggerNotificationAfterUpdate();
        });
        $this->bindEvent('model.afterDelete', function () {
            $this->revisionTriggerNotificationAfterDelete();
        });
    }

    public function revisionTriggerNotificationAfterCreate()
    {
        if(!$this->revisionTriggerNotificationEnabled) {
            return;
        }

        if(isset($this->revisionTriggerFields['createdHandlerAction'])) {
            $handler = $this->revisionTriggerFields['createdHandlerAction'];
            $handler[0]::{$handler[1]}($this);
        }
    }

    public function revisionTriggerNotificationAfterUpdate()
    {
        if(!$this->revisionTriggerNotificationEnabled) {
            return;
        }
        $arrayToCheck = array_except($this->revisionTriggerFields, 'createdHandlerAction');
        $arrayToCheckNew = [];
        foreach($arrayToCheck as $fields => $action) {
            $exp = explode("|", $fields); // or fields!
            if(count($exp) > 0) {
                foreach($exp as $f) {
                    $arrayToCheckNew[$f] = $action;
                }
            } else {
                $arrayToCheckNew[$fields] = $action;
            }
        }
        $dirty = $this->getDirty();
        foreach($dirty as $attribute => $value) {
            if(!isset($arrayToCheckNew[$attribute])) {
                continue;
            }
            $arrayToCheckNew[$attribute][0]::{$arrayToCheckNew[$attribute][1]}($this, array_get($this->original, $attribute), $value);
        }
    }

    public function revisionTriggerNotificationAfterDelete()
    {
        if(!$this->revisionTriggerNotificationEnabled) {
            return;
        }

        if(isset($this->revisionTriggerFields['deletedHandlerAction'])) {
            $handler = $this->revisionTriggerFields['deletedHandlerAction'];
            $handler[0]::{$handler[1]}($this);
        }

    }
}