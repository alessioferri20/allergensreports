<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Reports */

$this->title = 'Create report';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reports-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action_update' => false
    ]) ?>

</div>
