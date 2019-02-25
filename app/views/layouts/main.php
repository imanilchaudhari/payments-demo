<?php

use app\widgets\Alert;
?>
<?php $this->beginContent('@app/views/layouts/blank.php'); ?>

	<?=Alert::widget()?>

	<?php echo $content;?>

<?php $this->endContent(); ?>
