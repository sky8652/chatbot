<?php

return [
    'demo' => [

        'contexts' => [
            'welcomeUser' => '欢迎用户',

            'featureTest' => '功能点测试',

            'testGames' => '对话交互小游戏',

            'devTools' => '开发工具',

            'nlCases' => '自然语言测试用例',
        ],

        'dialog' => [
            'welcomeUser' => '欢迎您, {name}!! 这是您第{count}次访问.',

            'introduceDevTools' => 'CommuneChatbot 开发了一批用多轮对话管理机器人自己的工具.',

            'introduceGames' => 'CommuneChatbot 用多轮对话做了几个对话小游戏, 用于展示如何开发复杂的多轮对话任务. ',

            'chooseGames' => '请选择您想要尝试的小游戏 ',

            'chooseDevTools' => '请选择您要使用的开发工具: ',

            'nlTest' => 'CommuneChatbot 可以整合各种 NLU 单元作为中间件, 提供自然语言的语义解析, 实体提取等能力, 用于多轮对话. 目前已经对接 DuerOS, Rasa 等.
            
由于项目重点在于 "复杂多轮对话问题", 所以本 Demo 只做了几个自然语言的测试用例. ',

            'commandTest' => 'CommuneChatbot 也可以作为一个多轮对话形式的开发工具, 在各种即时通讯平台部署后, 可以直接在对话中使用类似 shell 的命令行.
            
系统给用户开放了一些默认命令, 输入 "#help" 可以随时查看',

            'helpInfo' => '随时输入 "?", 可以查看当前语境下的 "帮助" 信息. 如果开发者有定义过, 就能显示帮助信息. 不过目前 Demo 只有极少数场景写了帮助. ',
        ],




        'maze' => [
            'toward' => [
                'front' => '推开了面前的门.',
                'left' => '推开了左手边的门.',
                'right' => '推开了右手边的门.',
                'back' => '转身推开了后面的门.',
            ],
            'info' => [
                'location' => '当前所在房间编号是横坐标{x}, 纵坐标{y}, 面朝{direction}',
                'introduce' => '这是一个由25个房间组成的小迷宫, 每个房间都是正方形的, 四个方向有四张门.
在游戏里您只能发出指令说: 向前, 向后, 向左, 向右. 控制角色前进.
任何时候说 "退出" 则会退出游戏. ',

                'farewell' => '再见. 欢迎再来!',

                'welcome' => '欢迎来到方向迷宫!',

                'welcomeOldPlayer' => '欢迎您再次来到方向迷宫! 您玩过{total}次, 最好成绩{score}分.',

                'quit' => '迷宫游戏退出, 欢迎再来挑战!',



            ],

            'play' => [

                'born' => '游戏开始! 
我被传送到了一个神秘的迷宫, 自己赤手空拳还光着脚.  
房间里只有一个锁住的升降电梯, 什么别的都没有. 看来要拿到钥匙才能出去. 
前,后,左,右四面墙的中央各有一张可以打开的门.',


                'sameRoom' => '进入了同样的房间.',

                'back' => '我赶紧退了回去.',

                'win' => '我用找到的钥匙打开了电梯, 惴惴不安地走了进去.',

                'cancel' => '告诉您一个秘诀, 可以说"坐标"知道自己位置哦. ',

                'end' => '游戏结束! 您的得分是{score}. 恭喜您获得胜利!',

                'notice' => '没能明白您的意思. 您可以说"向前", "向后", "向左", "向右"来进入下一个房间, 或者说"退出"以退出游戏.',
            ],

            'dialog' => [
                'then' => '接下来往哪个方向走呢.',

                'oneMore' => '还想再来一局吗?',

                'wantIntro' => '您要听游戏介绍吗?',

                'start' => '您要开始这个游戏吗?',
            ],


        ],
    ],
];