<?php

class Project extends CActiveRecord {



    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'project';
    }

    public function getDbConnection() {
        return Yii::app()->db;
    }



}
