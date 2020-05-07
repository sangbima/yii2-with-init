<?php

namespace app\models\base;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Override delete method on yii\db\ActiveRecord
     * 
     * @return boolean
     */
    public function delete()
    {
        $model = parent::find()
            ->where(['id' => $this->id])
            ->one();
        $model->deleted_at = date('U');

        return $model->update();
    }

    /**
     * Digunakan untuk mengecek apakah record ada di tempat sampah atau tidak
     * 
     * @return array
     */
    public function trashed()
    {
        return parent::find()
            ->onCondition(['<>', 'deleted_at', 'null']);
    }

    /**
     * Digunakan untuk mengembalikan nilai deleted_at ke null
     * 
     * @return boolean
     */
    public function restore()
    {
        $model = parent::find()
            ->andWhere(['id' => $this->id])
            ->one();
        $model->deleted_at = null;

        return $model->update();
    }

    /**
     * Digunakan untuk mengambil semua record, termasuk record dengan deleted_at tidak null
     * 
     * @return array
     */
    public function withTrashed()
    {
        return parent::find();
    }

    /**
     * Digunakan untuk mengambil record dengan deleted_at tidak null
     * 
     * @return array
     */
    public function onlyTrashed()
    {
        return parent::find()
            ->andWhere(['IS NOT', 'deleted_at', new \yii\db\Expression('null')]);
    }

    /**
     * Digunakan untuk menghapus record secara permanent
     * 
     * @return boolean
     */
    public function forceDelete()
    {
        return parent::delete();
    }

    /**
     * Digunakan untuk mengambil semua record, dimana kolom deleted_at adalah null
     * 
     * @return array
     */
    public static function findWithoutTrash()
    {
        return parent::find()
            ->andWhere(['IS', 'deleted_at', new \yii\db\Expression('null')]);
    }
}
