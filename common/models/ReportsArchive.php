<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reports_archive".
 *
 * @property integer $a_id
 * @property integer $program_id
 * @property integer $company_id
 * @property string $archive_url
 * @property string $archived_date
 */
class ReportsArchive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reports_archive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'company_id', 'archive_url'], 'required'],
            [['program_id', 'company_id'], 'integer'],
            [['archive_url'], 'string'],
            [['archived_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'program_id' => 'Program ID',
            'company_id' => 'Company ID',
            'archive_url' => 'Archive Url',
            'archived_date' => 'Archived Date',
        ];
    }
}
