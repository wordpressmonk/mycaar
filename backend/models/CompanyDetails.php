<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company_details".
 *
 * @property integer $cmp_id
 * @property integer $user_id
 * @property string $company_name
 * @property string $company_url
 * @property string $company_email
 * @property string $company_logo
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CompanyDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'company_name', 'company_url', 'company_email', 'company_logo', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['user_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_name', 'company_url', 'company_email', 'company_logo'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cmp_id' => 'Cmp ID',
            'user_id' => 'User ID',
            'company_name' => 'Company Name',
            'company_url' => 'Company Url',
            'company_email' => 'Company Email',
            'company_logo' => 'Company Logo',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
