<?php

namespace Liyq\Laravel\Notifications\JPush;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Liyq\Laravel\Notifications\JPush\Channels\JpushChannel;
use JPush\Client as JPushClient;

class JPushServiceProvider extends ServiceProvider
{
    public function register() {
        // 注册 Jpush 通知驱动

        $this->app->make(ChannelManager::class)
            ->extend('jpush', function ($app) {
                $options = [
                    $app->config->get('notification.jpush.app_key'),
                    $app->config->get('notification.jpush.app_secret'),
                    $app->config->get('notification.jpush.log', null),
                ];
                return new JpushChannel(new JPushClient(...$options));
            });
    }
}