<?php

namespace api\controllers;

use Yii;
use api\controllers\common\RestController;
use humanized\location\models\location\CountrySearch;

class CountryController extends RestController {

    public $modelClass = 'humanized\location\models\location\Country';



    public function prepareDataProvider()
    {
        $model = new CountrySearch();
        
        return $model->search(Yii::$app->request->queryParams);
    }

}
