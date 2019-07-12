<?php


namespace Liyq\Laravel\Notifications\JPush;


class JPushMessage
{
    const IOS = 'ios';
    const ANDROID = 'android';
    const WP = 'winphone';

    public $alert;

    public $message;

    public $notifications = [];

    public $options = [];

    public function __construct(?string $alert = null) {
        if (is_string($alert)) {
            $this->setAlert($alert);
        }
    }

    public function setAlert(string $alert) {
        $this->alert = $alert;
        return $this;
    }

    public function setMessage(array $options, string $content = null) {
        $this->message = array_merge($options, [
            'msg_content' => $content
        ]);
        return $this;
    }

    public function setNotification(string $platform, string $alert, array $options = []) {
        $this->notifications[$platform] = [
            'alert'   => $alert,
            'options' => $options
        ];
        return $this;
    }

    public function setOptions(array $options) {
        $this->options = $options;
        return $this;
    }

}