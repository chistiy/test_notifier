<?php
namespace app\common\service;


use app\models\Notification;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;

class HttpRequestService
{
    /**
     * @throws Exception
     */
    public function saveNotificationToDb($dataToSave): bool|string
    {
        //Доп проверок на то что это email не надо. Фреймворк всё делает сам
        $notification = new Notification(); // По хорошему надо DI
        $notification->email_sender = $dataToSave['email_sender'];
        $notification->email_recipient = $dataToSave['email_recipient'];
        $notification->message = $dataToSave['message'];

        if (!$notification->save()) {
            return json_encode([
                'success' => false,
                'errors' => $notification->getErrors(),
            ]);
        }

        return json_encode([
            'success' => true,
            'message' => 'Уведомление успешно сохранено',
        ]);
    }

}