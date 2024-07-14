<?php
namespace app\common\service;

use app\models\Notification;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ConsumerService extends BaseObject
{
    public string $host;
    public int $port;
    public string $user;
    public string $password;
    public string $queue = 'queue_1'; //тож не очень хорошая практика

    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    /**
     * @throws Exception
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->initializeConnection();
    }

    private function initializeConnection(): void
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password
            );
            $this->channel = $this->connection->channel();
            $this->channel->queue_declare($this->queue, false, true, false, false);
        } catch (\Exception $e) {
            throw new Exception('Ошибка при установлении соединения: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     * @throws \ErrorException
     */
    public function consume(): void
    {
        if ($this->channel === null) {
            throw new Exception('Канал не заиничен.');
        }

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($this->queue, '', false, false, false, false, [$this, 'processMessage']);

        while (count($this->channel->callbacks)) {
            try {
                $this->channel->wait(null, false, 1600); //поставил тайм на всякий
            } catch (\Exception $e) {
                Yii::error("Ошибка при обработке сообщения: " . $e->getMessage(), 'notification');
                sleep(5);
                $this->channel->wait(null, false, 10);
            }
        }
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws InvalidConfigException
     */
    public function processMessage(AMQPMessage $message): void
    {
        $data = json_decode($message->body, true);
        $notification = Yii::createObject(Notification::class);
        $notification->email_sender = $data['email_sender'];
        $notification->email_recipient = $data['email_recipient'];
        $notification->message = $data['message'];

        if (!$notification->validate() || !$notification->save()) {
            throw new Exception('Ошибка при сохранении сообщения: ' . implode("; ", $notification->getErrors()));
        }
        $this->channel->basic_ack($message->delivery_info['delivery_tag']);
    }

    public function __destruct()
    {
        if ($this->channel !== null) {
            $this->channel->close();
        }
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
}
