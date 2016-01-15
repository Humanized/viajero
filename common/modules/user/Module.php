<?php

namespace common\modules\user;

/**
 * Humanized User Module
 * 
 * Provides several interfaces for dealing with Yii2 based user accounts
 * 
 * 
 */
class Module extends \yii\base\Module {

    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'common\modules\user\commands';
        }
    }

}
