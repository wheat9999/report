<?php

class Owner extends CActiveRecord {



    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'owner';
    }

    public function getDbConnection() {
        return Yii::app()->db;
    }



}
