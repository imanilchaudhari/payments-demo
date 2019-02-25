<?php

use yii\helpers\Url;

$this->title = 'Payment Services';
?>
<section class="product-listing">
    <div class="container-fluid">
        <p class="text-center"><a href="<?=Url::to(['/paypal/process'])?>"><img src="<?=Url::base()?>/images/paypal-button.png"></a></p>
    </div>
</section>
