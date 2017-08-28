<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reports".
 *
 * @property integer $id
 * @property string $report_date
 * @property string $patient
 *
 * @property Rows[] $rows
 * @property Allergens[] $allergens
 */
class Reports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['patient'], 'required'],
            [['report_date'], 'safe'],
            [['patient'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report_date' => 'Report Date',
            'patient' => 'Patient',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRows()
    {
        return $this->hasMany(Rows::className(), ['report_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllergens()
    {
        return $this->hasMany(Allergens::className(), ['id' => 'allergen_id'])->viaTable('rows', ['report_id' => 'id']);
    }
}
