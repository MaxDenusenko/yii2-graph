<?php

/* @var $this yii\web\View */
/* @var $files array */
/* @var $datum_code integer|string */

$this->title = 'Graph';

use app\widgets\Graph\Graph;
?>

<?= Graph::widget(['data' => $files, 'datum_key' => $datum_code]); ?>
