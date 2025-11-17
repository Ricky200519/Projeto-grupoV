<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About';
$this->registerJsFile('@web/js/about.js');
?>
<div class="site-about">
    <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>

    <p class="text-secondary">This is the About page. You may modify the following file to customize its content:</p>
</div>
