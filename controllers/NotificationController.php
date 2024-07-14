<?php

namespace app\controllers;

use app\common\service\HttpRequestService;
use app\models\Notification;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Controller;

class NotificationController extends Controller
{
    public function beforeAction($action): bool
    {
        if ($action->id === 'notification-on-http') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    } //костыль чтоб нормально и быстро работать локально

    private HttpRequestService $httpRequestService;

    public function __construct($id, $module, HttpRequestService $httpRequestService, $config = [])
    {
        $this->httpRequestService = $httpRequestService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws Exception
     * метод для получения по http
     *
     */
    public function actionNotificationOnHttp(): bool|string
    {

        return $this->httpRequestService->saveNotificationToDb(Yii::$app->request->post());
    }

    /**
     * @return array
     * метод для получения всех уведомлений
     */
    public function actionNotifications(): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Notification::find()->all();
    }
}
