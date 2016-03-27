<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // Override the urlManager component
        'urlManager' => [
            'class' => 'humanized\translation\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false, // Only considered when enablePrettyUrl is set to true
        // List all supported languages here
        // Make sure, you include your app's default language.
        ],
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
        // 'cache' => 'cache', // this enables RBAC caching
        ],
        'user' => [
            'identityClass' => 'humanized\user\models\common\User',
            'class' => 'yii\web\User',
        //   'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        'clihelper' => [
            'class' => 'humanized\clihelpers\Module',
        ],
        'translation' => [
            'class' => 'humanized\translation\Module',
        ],
        'user' => [
            'class' => 'humanized\user\Module',
            'root' => '*@humanized.be',
            'enableSignUp' => TRUE,
            'enableUserName' => FALSE,
            'enableUserVerification' => TRUE,
            'enableAdminVerification' => TRUE,
            'enableRBAC' => TRUE,
            'enableStatusCodes' => TRUE,
            'enableTokenAuthentication' => TRUE,
        ],
        'location' => [
            'class' => 'humanized\location\Module',
            'enableRemote' => TRUE,
            'remoteSettings' => [
                'uri' => 'localhost/dev/viajero/api/',
                'token' => 'AZCNZ6z-HSL-Kw24QO7U74u9guIc8NA63ogqO_uqw-KUpqC-mOTycuCwcfRBkP4VO-4cGGHlKaf1zrmrUzcxpvt6KFgrlZOzUP6d',
            ]
        ],
        'contact' => [
            'class' => 'humanized\contact\Module',
        ],
        'rbac' => [
            'class' => 'humanized\rbac\Module',
        ],
    ],
];
