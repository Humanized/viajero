<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container-fluid">

            <?php
            NavBar::begin([
                'brandLabel' => '<strong>VIAJERO</strong><br> Location Management System',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar navbar-default',
                ],
            ]);
            $menuItems = [
                    //     ['label' => 'Features', 'url' => ['/site/features']],
                    //     ['label' => 'Pricing', 'url' => ['/site/pricing']],
                    //    ['label' => 'Download', 'url' => ['/site/download']],
                    /*
                      ['label' => 'Home', 'url' => ['/site/index']],
                      ['label' => 'About', 'url' => ['/site/about']],

                      ['label' => 'Location Managment', 'url' => ['/contact/location/index']],
                      ['label' => 'User Managment', 'url' => ['/user/admin/index']],
                     *             * 
                     */
            ];

            echo NavX::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => array_merge($menuItems, humanized\user\components\GUIHelper::getUserMenuItems()),
            ]);
            NavBar::end();
            ?>
        </div>
        <div id="main" class="container">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
            <?= Alert::widget() ?>
            <?= $content ?>

        </div>


        <footer class="footer">
            <?php
            NavBar::begin([

                'options' => [
                    'class' => 'navbar navbar-default',
                ],
            ]);

            $menuItems = [
                ['label' => '<span class="glyphicon glyphicon-globe"></span><br>Locations', 'url' => ['/contact/location/index']],
                ['label' => '<span class="glyphicon glyphicon-bullhorn"></span><br>Translations', 'url' => ['/translation/admin/index']],
                ['label' => '<span class="glyphicon glyphicon-user"></span><br>Account', 'visible' => NULL !== \Yii::$app->user->getId(), 'url' => ['/user/account/index', 'id' => \Yii::$app->user->getId()]],
                ['label' => '<span class="glyphicon glyphicon-book"></span><br>Docs', 'url' => ['/site/documentation']],
                    /*
                      ['label' => 'Home', 'url' => ['/site/index']],
                      ['label' => 'About', 'url' => ['/site/about']],

                      ['label' => 'Location Managment', 'url' => ['/contact/location/index']],
                      ['label' => 'User Managment', 'url' => ['/user/admin/index']],
                     *             * 
                     */
            ];

            echo NavX::widget([
                'encodeLabels' => false,
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
