<?php namespace Yfktn\Yfktnutil\Classes;

use Backend\Facades\BackendAuth;
use Yfktn\YfktnUtil\Models\EventLogger as ModelsEventLogger;

class EventLogger
{
    public static function log($triggerType, $triggerId, $why, $description = null, $operatorId = null)
    {
        $data = [
            'trigger_type' => $triggerType,
            'trigger_id' => $triggerId,
            'why' => $why,
            'description' => $description
        ];

        if ($operatorId !== null) {
            $data['operator_id'] = $operatorId;
        } else {
            $data['operator_id'] = BackendAuth::getUser() === null? 0 : BackendAuth::getUser()->id;
        }
        return ModelsEventLogger::create($data);
    }
}