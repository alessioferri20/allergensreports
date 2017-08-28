<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "allergens".
 *
 * @property integer $id
 * @property string $name
 * @property double $threshold
 *
 * @property Rows[] $rows
 * @property Reports[] $reports
 */
class Allergens extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'allergens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'threshold'], 'required'],
            [['threshold'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'threshold' => 'Threshold',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRows()
    {
        return $this->hasMany(Rows::className(), ['allergen_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Reports::className(), ['id' => 'report_id'])->viaTable('rows', ['allergen_id' => 'id']);
    }
}
