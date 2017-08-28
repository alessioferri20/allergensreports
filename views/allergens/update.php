<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Allergens */

$this->title = 'Update Allergens: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Allergens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allergens-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
