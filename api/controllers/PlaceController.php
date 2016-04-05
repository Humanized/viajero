<?php

namespace api\controllers;

use Yii;
use api\controllers\common\RestController;
use humanized\location\models\location\LocationSearch;

class PlaceController extends RestController
{

    public $modelClass = 'humanized\location\models\location\Location';

    public function prepareDataProvider()
    {
        $model = new LocationSearch(['q' => Yii::$app->request->get('q'), 'uid' => Yii::$app->request->get('uid'), 'country_id' => Yii::$app->request->get('country')]);
        return $model->search(Yii::$app->request->queryParams);
    }

}
