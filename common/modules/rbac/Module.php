<?php

namespace common\modules\rbac;

/**
 * Humanized RBAC Module for Yii2
 * 
 * Provides several interfaces for dealing with Yii2 RBAC implementation
 * 
 * @name Yii2 RBAC Module CLass 
 * @version 0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-rbac
 * 
 */
class Module extends \yii\base\Module {

    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'common\modules\rbac\commands';
        }
    }

}
