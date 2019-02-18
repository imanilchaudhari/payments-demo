<?php

use app\models\Option;
use yii\helpers\Inflector;
use yii\helpers\Url;

$this->title = Option::get('tagline') . ' - ' . Option::get('sitetitle');
?>
<section class="product-listing">
    <div class="container-fluid">
        <h1>Hello World</h1>

        <p class="text-center"><a href="<?=Url::to(['/site/process'])?>"><img src="<?=Url::base()?>/images/paypal-button.png"></a></p>
    </div>
</section>
