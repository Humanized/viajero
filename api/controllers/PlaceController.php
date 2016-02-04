<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use humanized\location\models\location\LocationSearch;

class PlaceController extends ActiveController {

    public $modelClass = 'humanized\location\models\Location';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        return $behaviors;
    }

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
        $model = new LocationSearch(['q' => Yii::$app->request->get('q'), 'country_id' => Yii::$app->request->get('country')]);
        return $model->search(Yii::$app->request->queryParams);
    }

}
