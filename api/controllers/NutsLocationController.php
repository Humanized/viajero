<?php

namespace api\controllers;

use Yii;
use api\controllers\common\RestController;
use humanized\location\models\nuts\NutsLocationSearch;

class NutsLocationController extends RestController
{

    public $modelClass = 'humanized\location\models\nuts\NutsLocation';

    public function prepareDataProvider()
    {
        $model = new NutsLocationSearch(['country_id' => Yii::$app->request->get('country'),'postcode' => Yii::$app->request->get('postcode')]);
        return $model->search(Yii::$app->request->queryParams);
    }

}
