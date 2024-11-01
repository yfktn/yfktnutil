<?php namespace Yfktn\YfktnUtil\Models;

use System\Models\Revision as RevisionBase;

/**
 * Revision history model
 *
 * @package october\system
 * @author Alexey Bobkov, Samuel Georges
 */
class Revision extends RevisionBase
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_yfktnutil_revision';
}
