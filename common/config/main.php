<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
            'itemTable' => 'privilege',
            'itemChildTable' => 'privilege_tree',
            'assignmentTable' => 'user_privilege',
            'ruleTable' => 'rule',
            'cache' => 'cache', // this enables RBAC caching
        ],
        'user' => [
            'identityClass' => 'humanized\user\models\common\User',
            'class' => 'yii\web\User',
        //   'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'humanized\user\Module',
            'enableUserName' => FALSE,
            
           // 'enableSignUp' => FALSE,
        ],
        'contact' => [
            'class' => 'humanized\contact\Module',
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module',
        ],
    ],
];
