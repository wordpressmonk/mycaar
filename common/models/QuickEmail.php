<?php

namespace common\models;
use common\models\User;
use Yii;

/**
 * This is the model class for table "quick_email".
 *
 * @property integer $q_id
 * @property integer $c_id
 * @property string $to_email
 * @property string $from_email
 * @property string $subject
 * @property string $message
 * @property integer $status
 * @property string $datetime
 */
class QuickEmail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quick_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'user_id', 'to_email', 'from_email', 'subject', 'message'], 'required'],
            [['c_id', 'user_id','status'], 'integer'],
            [['message'], 'string'],
            [['to_email', 'from_email', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'q_id' => 'Q ID',
            'c_id' => 'C ID',
            'user_id' => 'User ID',
            'to_email' => 'To Email',
            'from_email' => 'From Email',
            'subject' => 'Subject',
            'message' => 'Message',
            'status' => 'Status',
            'datetime' => 'Datetime',
        ];
    }
	
 	 public function sendEmail($userid,$password,$to_email)
    {		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $userid,
        ]);		
        if (!$user) {
            return false;
        }
        
        return Yii::$app
            ->mail
            ->compose(
                ['text' => 'passwordSend-text'],
                ['user' => $user,'password'=>$password]
            )
            ->setFrom('arivu.ilan@gmail.com')
            ->setTo($to_email)
            ->setSubject('YOUR VERIFIED EMAIL ID')
            ->send();
    } 
	
}
