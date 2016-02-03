<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use humanized\location\models\location\CountrySearch;

class CountryController extends ActiveController {

    public $modelClass = 'humanized\location\models\Country';

    public function actions()
    {
        $actions = parent::actions();

        // Only allow read-only actions (for now)
        unset($actions['delete'], $actions['update'], $actions['create']);

        //customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $model = new CountrySearch();
        return $model->search(Yii::$app->request->queryParams);
    }

}
