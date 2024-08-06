<?php namespace Yfktn\Yfktnutil\Classes\Traits;

use DateTime;
use Db;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MissingAttributeException;
use LogicException;
use October\Rain\Database\Traits\Revisionable;
use RuntimeException;
/**
 * Menambahkan pencatatan log untuk proses penghapusan dan pembuatan data.
 * Jika yang dirubah pada nilai field adalah "id" maka:
 * - jika old_value = NULL dan new_value = nilaiid => ini pembuatan data baru
 * - jika old_value = nilaiid dan new_value = null => ini proses penghapusan
 * 
 * Untuk softdelete mengikuti proses yang ada sebelumnya!
 */
trait RevisionableWithCreate
{
    use Revisionable {
        Revisionable::initializeRevisionable as parentInitializeRevisionable;
        Revisionable::revisionableAfterDelete as parentRevisionableAfterDelete;
    }

    public function initializeRevisionable()
    {
        $this->parentInitializeRevisionable();

        $this->bindEvent('model.afterCreate', function () {
            $this->revisionableAfterCreate();
        });
    }

    public function revisionableAfterCreate()
    {
        if (!$this->revisionsEnabled) {
            return;
        }

        $relation = $this->getRevisionHistoryName();
        $relationObject = $this->{$relation}();
        $revisionModel = $relationObject->getRelated();

        $toSave = [];

        $toSave[] = [
            'field' => 'id',
            'old_value' => null,
            'new_value' => $this->getKey(),
            'revisionable_type' => $relationObject->getMorphClass(),
            'revisionable_id' => $this->getKey(),
            'user_id' => $this->revisionableGetUser(),
            'cast' => null,
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ];

        // Nothing to do
        if (!count($toSave)) {
            return;
        }

        Db::table($revisionModel->getTable())->insert($toSave);
        $this->revisionableCleanUp();
    }
    
    /**
     * Override supaya dapat melakukan log penghapusan!
     * @return void 
     * @throws InvalidCastException 
     * @throws MissingAttributeException 
     * @throws LogicException 
     * @throws RuntimeException 
     */
    public function revisionableAfterDelete()
    {
        if (!$this->revisionsEnabled) {
            return;
        }

        $softDeletes = in_array(
            \October\Rain\Database\Traits\SoftDelete::class,
            class_uses_recursive(get_class($this))
        );

        if($softDeletes) {
            $this->parentRevisionableAfterDelete();
            return;
        }

        $relation = $this->getRevisionHistoryName();
        $relationObject = $this->{$relation}();
        $revisionModel = $relationObject->getRelated();

        $toSave = [
            'field' => 'id',
            'old_value' => $this->getKey(),
            'new_value' => null,
            'revisionable_type' => $relationObject->getMorphClass(),
            'revisionable_id' => $this->getKey(),
            'user_id' => $this->revisionableGetUser(),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ];

        Db::table($revisionModel->getTable())->insert($toSave);
        $this->revisionableCleanUp();
    }
}