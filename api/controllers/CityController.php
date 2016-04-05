<?php

namespace api\controllers;

use Yii;
use api\controllers\RestController;
use humanized\location\models\location\CitySearch;

class CityController extends RestController
{

    public $modelClass = 'humanized\location\models\location\City';

    public function prepareDataProvider()
    {
        $model = new CitySearch(['uid' => Yii::$app->request->get('uid')]);
        return $model->search(Yii::$app->request->queryParams);
    }

}
