<?php



return [

    'chatbotName' => 'demo',

    'debug' => true,

    'configBindings' => [
        \Commune\Chatbot\App\Platform\ConsoleConfig::class => [
            'allowIPs' => ['127.0.0.1'],
        ],
    ],
    'components' => [
        // demo 组件
        \Commune\Components\Demo\DemoComponent::class,
        // 闲聊组件
        \Commune\Components\SimpleChat\SimpleChatComponent::class,
        // 对话游戏组件
        \Commune\Components\Story\StoryComponent::class,
        // 简单问答组件
        \Commune\Components\SimpleWiki\SimpleWikiComponent::class,
        \Commune\Components\UnheardLike\UnheardLikeComponent::class,
    ],
    'processProviders' => [
    ],
    'conversationProviders' => [
        \Commune\Chatbot\App\Drivers\Demo\CacheServiceProvider::class,
        \Commune\Chatbot\App\Drivers\Demo\SessionServiceProvider::class,
    ],
    'chatbotPipes' =>
        [
            'onUserMessage' => [
                \Commune\Chatbot\App\ChatPipe\UserMessengerPipe::class,
                \Commune\Chatbot\App\ChatPipe\ChattingPipe::class,
                \Commune\Chatbot\OOHost\OOHostPipe::class,
            ],
        ],
    'translation' =>
        [
            'loader' => 'php',
            'resourcesPath' => __DIR__ . '/../../src/Chatbot/App/trans',
            'defaultLocale' => 'zh',
            'cacheDir' => NULL,
        ],
    'logger' =>
        [
            'path' => __DIR__ . '/cache/tmp.log',
            'days' => 0,
            'level' => 'debug',
            'bubble' => true,
            'permission' => NULL,
            'locking' => false,
        ],
    'defaultMessages' =>
        [
            'platformNotAvailable' => 'system.platformNotAvailable',
            'chatIsTooBusy' => 'system.chatIsTooBusy',
            'systemError' => 'system.systemError',
            'farewell' => 'dialog.farewell',
            'messageMissMatched' => 'dialog.missMatched',
        ],
    'eventRegister' =>[
        
    ],

    'defaultSlots' => [
        // 系统默认的slots, 所有的reply message 都会使用
        // 多维数组会被抹平为 self.name 这样的形式
        // default reply slots
        // multi-dimension array will be flatten to dot pattern
        // such as 'self.name'
        'self' => [
            'name' => 'CommuneChatbot',
            'project' => 'commune/chatbot',
            'fullname' => 'commune/chatbot demo',
            'author' => 'thirdgerb',
            'email' => 'thirdgerb@gmail.com',
            'desc' => '多轮对话机器人开发框架',
        ]
    ],
        
    'host' => [

        // 在这里用 PSR-4 规范定义出系统启动时要注册Context的路径
        'autoloadPsr4' => [
            "Commune\\Demo\\" => __DIR__ .'/../src/'
        ],

        'rootContextName' => \Commune\Components\Demo\Contexts\DemoHome::class,

        // 新手教程: 添加机器人, 作为一个启动场景.
        'sceneContextNames' => [

            // test 是场景名, 用类名来标记 Context
            'test' => \Commune\Demo\HelloWorld::class,

            // 一阶多轮对话 教程.
            'firstOrder' => \Commune\Demo\FirstOrderConvo::class,

            // 用 depend 定义的一阶对话
            'userInfo' => 'demo.lesions.user-info',

            // n 阶多轮对话的测试用例.
            'nOrder' => \Commune\Demo\WelcomeUser::class,

            // 测试意图相关
            'intent' => \Commune\Demo\TestIntents::class,

            // 测试上下文记忆
            'memory' => \Commune\Demo\TestMemory::class,
        ],

        'sessionPipes' => [
            \Commune\Chatbot\App\SessionPipe\EventMsgPipe::class,
            \Commune\Chatbot\App\SessionPipe\MarkedIntentPipe::class,
            \Commune\Chatbot\App\Commands\UserCommandsPipe::class,
            \Commune\Chatbot\App\Commands\AnalyserPipe::class,

            // \Commune\Components\Rasa\RasaSessionPipe::class,

            \Commune\Chatbot\App\SessionPipe\NavigationPipe::class,
        ],

        'hearingFallback' => \Commune\Components\SimpleChat\Callables\SimpleChatAction::class,
    ] ,

];