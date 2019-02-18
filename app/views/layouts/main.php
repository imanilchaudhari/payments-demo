<?php

use app\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<?php $this->beginContent('@app/views/layouts/blank.php'); ?>

	<?=Alert::widget()?>

	<?php echo $content;?>

<?php $this->endContent(); ?>
