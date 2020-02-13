<?php

/* @var $this View */
/* @var $content string */


use app\assets\AppAsset;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\navigation\Breadcrumbs;
use macgyer\yii2materializecss\widgets\Alert;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

        <header class="page-header navbar-fixed">
            <nav class="nav-center white" role="navigation">
                <div class="nav-wrapper container">
                    <ul>
                        <li><a title="Datums" href="<?= Url::to(['/datums'])?>"><i class="material-icons left">archive</i>Datums</a></li>
                        <li><a title="Files" href="<?= Url::to(['/files'])?>""><i class="material-icons left">attach_file</i>Files</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper">
            <main class="content">

                <div class="graph_container">

                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <?= Alert::widget() ?>

                <?= $content ?>
                </div>
            </main>

            <a href="javascript:" id="return-to-top"><i class="material-icons">keyboard_arrow_up</i></a>

            <footer class="footer page-footer">
                <div class="footer-copyright">
                    <div class="container">
                        <?= Yii::powered() ?>
                    </div>
                </div>
            </footer>
        </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
