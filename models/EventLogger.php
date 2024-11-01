<?php namespace Yfktn\YfktnUtil\Models;

use Model;

/**
 * Model
 */
class EventLogger extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'yfktn_yfktnutil_event';

    // add fillable property
    protected $fillable = [
        'trigger_type',
        'trigger_id',
        'why',
        'description',
        'operator_id'
    ];
    
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
