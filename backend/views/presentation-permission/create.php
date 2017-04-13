<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PresentationPermission */

$this->title = 'Create Presentation Permission';
$this->params['breadcrumbs'][] = ['label' => 'Presentation Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presentation-permission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
