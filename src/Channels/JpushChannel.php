<?php


namespace Liyq\Laravel\Notifications\JPush\Channels;

use Illuminate\Notifications\Notification;
use JPush\Client as JPushClient;
use JPush\PushPayload;
use Liyq\Laravel\Notifications\JPush\JPushMessage;
use Liyq\Laravel\Notifications\JPush\JPushSender;

class JpushChannel
{
    protected $client;

    public function __construct(JpushClient $client) {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification) {

        if (!($to = $notifiable->routeNotificationFor('jpush', $notification)) instanceof JPushSender) {
            return;
        }

        $message = $notification->toJpush($notifiable);
        if (is_string($message)) {
            $message = new JPushMessage($message);
        }
        try {
            $this->push($to, $message);
        } catch (\Throwable|\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 推送信息
     *
     * @param JPushSender  $sender
     * @param JPushMessage $message
     *
     * @return array
     */
    private function push(JPushSender $sender, JPushMessage $message) {
        $pusher = $this->client->push();

        $pusher = $this->setAudiences($pusher, $sender->audience);
        $pusher->setPlatform($sender->platform);

        if ($message->alert) {
            $pusher->setNotificationAlert($message->alert);
        }
        if ($message->message) {
            $pusher->message($message->message['msg_content'], $message->message);
        }

        foreach ($message->notifications as $platform => $data) {
            switch ($platform) {
                case JPushMessage::IOS :
                    $pusher->iosNotification($data['alert'], $data['options']);
                    break;
                case JPushMessage::ANDROID:
                    $pusher->androidNotification($data['alert'], $data['options']);
                    break;
                case JPushMessage::WP:
                    $pusher->addWinPhoneNotification($data['alert'], $data['options']);
                    break;
                default:
                    break;
            }
        }
        $pusher->options($message->options);

        return $pusher->send();
    }


    public function setAudiences(PushPayload $payload, array $audiences) {
        $methods = [
            'tag'             => 'Tag',
            'tag_and'         => 'TagAnd',
            'tag_not'         => 'TagNot',
            'alias'           => 'Alias',
            'registration_id' => 'RegistrationId',
            'segment'         => 'SegmentId',
            'abtest'          => 'Abtest',
        ];
        foreach ($audiences as $key => $audience) {
            $method = isset($methods[$key]) ? "add{$methods[$key]}" : "";
            if (method_exists($payload, $method)) {
                $payload->$method($audience);
            }
        }
        return $payload;
    }
}