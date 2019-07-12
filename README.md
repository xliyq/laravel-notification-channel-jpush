# 极光推送在 Laravel 通知的支持

## 要求
* php >= 7.1
* Laravel >= 5.5

## 安装
```
composer required liyq/laravel-notification-channel-jpush
```

## 配置
在 `config/notification.php`中进行如下配置
```php

return [
    ....
    
    'jpush'=>[
        'app_key'=>env('JPUSH_APP_KEY',''),
        'app_secret'=>env('JPUSH_MASTER_SECRET',''),
        'log'=>env('JPUSH_LOG'),
    ],

]
```

然后在 `.env` 文件中进行配置：

```text
JPUSH_APP_KEY=
JPUSH_MASTER_SECRET=
JPUSH_LOG=
```

## 使用
### 数据模型类
```php
<?php

use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable{
    
    protected function routeNotificationForJpush(){
        return  \Liyq\Laravel\Notifications\JPush\JPushSender::create('all',['alias'=>'']);
    }
    
}
```

### 通知类
```php
<?php
class DemoNotification extends \Illuminate\Notifications\Notification{
    
    public function toJpush($notification){
        $message = new \Liyq\Laravel\Notifications\JPush\JPushMessage();
        $message->setAlert('Alert');
        $message->setMessage([
            'title'=>'标题',
            '_open_page'=>'点击打开的页面名称',
            'extrs'=>[],
        ],'message');
        
        $message->setNotification('android',[]);
        return $message;
    }
    
    public function via(){
        return ['jpush'];
    }
}
```
