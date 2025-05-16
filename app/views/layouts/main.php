<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            //['label' => 'Home', 'url' => ['default/index']],
            //['label' => 'About', 'url' => ['default/about']],
            //['label' => 'Contact', 'url' => ['default/contact']],
            //['label' => 'Пользователи', 'url' => ['user']],
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()
                ? ['label' => 'Главная', 'url' => ['admin/default']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()
                ? ['label' => 'Пользователи', 'url' => ['admin/user']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isManager()
                ? ['label' => 'Главная', 'url' => ['manager/default']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isClient()
                ? ['label' => 'Главная', 'url' => ['client/default']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isPartner()
                ? ['label' => 'Главная', 'url' => ['partner/default']] : '',
            Yii::$app->user->isGuest
                //? ['label' => 'Login', 'url' => ['default/login']]
                ? ''
                : '<li class="nav-item">'
                    . Html::beginForm(['/default/logout'])
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->email . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light d-none">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; <a href="/">platforma108.com</a> <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end">

            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
