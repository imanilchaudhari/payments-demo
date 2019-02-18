<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name' => 'robots',
    'content' => 'noindex, nofollow',
]);
?>

<div class="container">
    <div class="additional-pages-wrapper errorpage-wrapper">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="errorpage-wrapper-inner">
        <p><img src="/images/404.png" alt=""></p>
        <p><?= nl2br(Html::encode($message)) ?>.</p>
        <p>Let's start again from the <a href="#">Home Page</a></p>
    </div>
    <h2 class="thank-you-msg">Thank You.</h2>
    </div>
</div>
