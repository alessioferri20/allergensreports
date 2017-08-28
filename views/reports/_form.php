<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\AllergensSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Reports */
/* @var $form yii\widgets\ActiveForm */

$allergens = Yii::$app->getDb()->createCommand("SELECT * FROM allergens")->queryAll();

if($action_update)
{
  $old_allergens = Yii::$app->getDb()->createCommand("SELECT a.*, r.allergen_value
                                                      FROM allergens as a, rows as r
                                                      WHERE a.id = r.allergen_id
                                                      AND r.report_id = ". $model->id)->queryAll();
}

$allergens_json = '\'[';
$index = 0;

foreach($allergens as $allergen)
{
  $index++;

  $allergens_json .= '{"id":"'. $allergen['id'] .'","name":"'. $allergen['name'] .'"}';

  if($index < count($allergens))
  {
    $allergens_json .= ',';
  }
}

$allergens_json .= ']\'';

$script = <<< JS
  var allergeni = JSON.parse($allergens_json);

  $("#add_allergene").on('click', function(event)
  {
    event.preventDefault();

    var allergeni_index = $('.field-reports-allergen', $('#allergeni')).length + 1

    var allergene_obj = '<div class="form-group field-reports-allergen required">' +
      '<select type="text" class="form-control" style="display: inline-table; width: auto;" name="allergen_' + allergeni_index + '_id" aria-required="true" aria-invalid="true">'
      + '<option value="-1">-- seleziona allergene --</option>'

    for(var i = 0; i < allergeni.length; i++)
    {
        allergene_obj += '<option value="' + allergeni[i].id +'">' + allergeni[i].name + '</option>'
    }

    allergene_obj += '</select><input type="text" class="form-control" style="display: inline-table; width: auto;" name="allergen_' +
    allergeni_index + '_value" aria-required="true" aria-invalid="true"></div>'

    $('#allergeni').append(allergene_obj)
  })

  $('.delete-allergen').on('click', function(event)
  {
    event.preventDefault();
    $('#' + $(this).attr('data-attr')).val('')
    $('#' + $(this).attr('data-attr')).closest('tr').hide()
  })
JS;
$this->registerJs($script);
?>

<div class="reports-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'patient')->textInput(['maxlength' => true]) ?>

    <button id="add_allergene" class="btn btn-primary">Add allergen</button>
    <br></br>
    <div id="allergeni">

    </div>

    <?php if($action_update) { ?>
      <h3>Allergens</h3>
      <table class="table table-striped table-bordered detail-view">
        <?php $index=0; foreach($old_allergens as $allergen) { $index++; ?>
        <tr>
          <th><?php echo $allergen['name']; ?></th>
          <td><input name="old_allergen_<?php echo $index; ?>_id" value="<?php echo $allergen['id']; ?>" type="hidden"/>
            <input id="old_allergen_<?php echo $index; ?>" name="old_allergen_<?php echo $index; ?>_value" value="<?php echo $allergen['allergen_value']; ?>"/>
            <button class="btn delete-allergen btn-danger" data-attr="old_allergen_<?php echo $index; ?>">x</button></td>
        </tr>
        <?php } ?>
      </table>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
