<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reports */

$this->title = $model->patient;

$allergens = Yii::$app->getDb()->createCommand("SELECT a.*, r.allergen_value
                                                FROM allergens as a, rows as r
                                                WHERE a.id = r.allergen_id
                                                AND r.report_id = ". $model->id)->queryAll();

$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reports-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'report_date',
            'patient',
        ],
    ]) ?>

    <h3>Allergens</h3>
    <table class="table table-striped table-bordered detail-view">
      <?php foreach($allergens as $allergen) { ?>
      <tr>
        <th><?php echo $allergen['name']; ?></th>
        <td <?php if($allergen['allergen_value'] > $allergen['threshold']) echo "style='color: red'"; ?>><?php echo $allergen['allergen_value']; ?></td>
      </tr>
      <?php } ?>
    </table>
</div>
