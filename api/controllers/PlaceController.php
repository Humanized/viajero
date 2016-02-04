<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use humanized\location\models\location\LocationSearch;

class PlaceController extends ActiveController {

    public $modelClass = 'humanized\location\models\Location';

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

        $model = new LocationSearch(['country_id' => Yii::$app->request->get('country')]);
        return $model->search(Yii::$app->request->queryParams);
    }

}
