<?php


namespace Liyq\Laravel\Notifications\JPush;


class JPushSender
{
    public $platform;

    public $audience = [];

    /**
     * 创建推送平台及目标
     *
     * @param string|array $platform
     * @param  array       $audience
     *
     * @return JPushSender
     */
    public static function create($platform, array $audience) {
        return new static(compact('platform', 'audience'));
    }


    /**
     * Sender constructor.
     *
     * @param array|null $payload
     */
    public function __construct(?array $payload = null) {
        if (isset($payload['platform'])) {
            $this->setPlatform($payload['platform']);
        }
        if (isset($payload['audience'])) {
            $this->setAudience($payload['audience']);
        }
    }

    /**
     * 设置推送平台
     *
     * @param  string|array $platform
     *
     * @return $this
     */
    public function setPlatform($platform) {
        $this->platform = $platform;
        return $this;
    }

    /**
     * 设置推送目标
     *
     * @param array $audience
     *
     * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/#_7
     * @return $this
     */
    public function setAudience(array $audience) {
        $this->audience = $audience;
        return $this;
    }
}