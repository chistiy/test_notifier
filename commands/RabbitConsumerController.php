<?php

namespace app\commands;

use app\common\service\ConsumerService;
use yii\base\Exception;
use yii\console\Controller;

class RabbitConsumerController extends Controller
{
    // Решил сделать через команду, чтобы можно было на супервизор поставить

    /**
     * @throws Exception
     * @throws \ErrorException
     */
    public function actionIndex(): void
    {
        //ВОТ ТАК ДЕЛАТЬ НЕ НАДО.
        // Были небольшие проблемы с конфигами, времени искать в чём именно не было,
        // по этому просто передал в тупую в качестве параметров
        $consumerService = new ConsumerService([
            'queue' => 'queue_1',
            'host' => 'rabbitmq',
            'port' => 5672,
            'user' => 'user',
            'password' => 'password'
        ]);

        $consumerService->consume();
    }
}
