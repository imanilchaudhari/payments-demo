<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

// Canonical
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->absoluteUrl]);

// Hreflang
$this->registerLinkTag(['rel' => 'alternate', 'href' => Yii::$app->request->absoluteUrl, 'hreflang' => 'en']);

// Favicon
$this->registerLinkTag(['rel' => 'icon', 'href' => Yii::getAlias('@web/favicon.ico'), 'type' => 'image/x-icon']);

$this->beginPage();

$baseUrl = Yii::$app->getRequest()->getBaseUrl();
$scriptUrl = Yii::$app->getRequest()->getScriptUrl();
$hostInfo = Yii::$app->getRequest()->getHostInfo();

$this->registerJs("var Yii = Yii || {}; Yii.app = {scriptUrl: '{$scriptUrl}', baseUrl: '{$baseUrl}',  hostInfo: '{$hostInfo}'};", $this::POS_HEAD);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?></title>
	<?php $this->head() ?>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php $this->beginBody() ?>
	<?php echo $content; ?>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
