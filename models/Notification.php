<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id
 * @property string $email_sender
 * @property string $email_recipient
 * @property string $message
 */
class Notification extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'notification';
    }

    public function rules(): array
    {
        return [
            [['email_sender', 'email_recipient', 'message'], 'required'],
            [['email_sender', 'email_recipient'], 'email'],
            [['message'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email_sender' => 'Email отправителя',
            'email_recipient' => 'Email получателя',
            'message' => 'Сообщение',
        ];
    }

}
