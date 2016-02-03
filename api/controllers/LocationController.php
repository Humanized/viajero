<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\Location;

class LocationController extends ActiveController {

    public $modelClass = 'common\models\Location';

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

        $model = new Location();
        return $model->search(Yii::$app->request->queryParams);
    }

}
