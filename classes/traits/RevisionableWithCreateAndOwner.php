<?php namespace Yfktn\Yfktnutil\Classes\Traits;

use DateTime;
use Db;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MissingAttributeException;
use LogicException;
use October\Rain\Database\Traits\Revisionable;
use RuntimeException;
/**
 * Catat log perubahan, di mana jika pada revision default milik october
 * akan menganggap bahwa data yang dicatat adalah datanya tidak terhapus.
 * Sedangkan di sini, kita dapat menambahkan data_owner_id, sehingga saat
 * data yang berkaitan dengan log ini dihapus, kita dapat melakukan proses
 * pencatatan terhadap siapa yang memiliki data tersebut! Misalnya file yang
 * dimiliki oleh suatu item yang kita tracking nilai perubahannya, jika file
 * tersebut dihapus, akan susah untuk ditracking proses penghapusan/update
 * terhadap data file tersebut. untuk itu ini dibuatkan feature ini.
 * 
 * Lakukan proses override pada getRevisionableDataOwnerId() untuk mengembalikan
 * data owner id yang benar! Pada file attachment misalnya, ini adalah nilai dari
 * return $this->attachment_id.
 * Jika yang dirubah pada nilai field adalah "id" maka:
 * - jika old_value = NULL dan new_value = nilaiid => ini pembuatan data baru
 * - jika old_value = nilaiid dan new_value = null => ini proses penghapusan
 * 
 * Untuk softdelete mengikuti proses yang ada sebelumnya!
 */
trait RevisionableWithCreateAndOwner
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
            'data_owner_id' => $this->revisionableDataOwnerId(),
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
     * revisionableAfterUpdate event
     */
    public function revisionableAfterUpdate()
    {
        if (!$this->revisionsEnabled) {
            return;
        }

        $relation = $this->getRevisionHistoryName();
        $relationObject = $this->{$relation}();
        $revisionModel = $relationObject->getRelated();

        $toSave = [];
        $dirty = $this->getDirty();
        foreach ($dirty as $attribute => $value) {
            if (!in_array($attribute, $this->revisionable)) {
                continue;
            }

            $toSave[] = [
                'field' => $attribute,
                'old_value' => array_get($this->original, $attribute),
                'new_value' => $value,
                'data_owner_id' => $this->revisionableDataOwnerId(),
                'revisionable_type' => $relationObject->getMorphClass(),
                'revisionable_id' => $this->getKey(),
                'user_id' => $this->revisionableGetUser(),
                'cast' => $this->revisionableGetCastType($attribute),
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ];
        }

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
            'data_owner_id' => $this->revisionableDataOwnerId(),
            'revisionable_type' => $relationObject->getMorphClass(),
            'revisionable_id' => $this->getKey(),
            'user_id' => $this->revisionableGetUser(),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ];

        Db::table($revisionModel->getTable())->insert($toSave);
        $this->revisionableCleanUp();
    }

    /**
     * Override ini untuk mendapatkan nilai owner id nya yang benar!
     * @return mixed 
     */
    public function revisionableDataOwnerId()
    {
        if (method_exists($this, 'getRevisionableDataOwnerId')) {
            return $this->getRevisionableDataOwnerId();
        }
        return $this->getKey();
    }
}