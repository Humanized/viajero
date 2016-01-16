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
            'identityClass' => 'common\modules\user\models\User',
            'class' => 'yii\web\User',
        //   'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'common\modules\user\Module',
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module',
        ],
    ],
];
