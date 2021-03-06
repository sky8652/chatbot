<?php


namespace Commune\Chatbot\OOHost\Context\Intent;


use Commune\Support\Option;

/**
 *
 * @property-read string $signature
 *  命令的写法. 同时也会定义参数
 * 写法参考laravel 的 command
 *
 * '命令名 {参数1 : 简介}
 *      {可选参数2? : 简介}
 *      {--n|name : 选项介绍}
 * '
 *
 *
 * @property-read array[] $regex
 * 正则匹配. 是一个二维数组.
 * 每一组元素是一个正则表达
 * [ string $patterns, string ...$matches] 用这种方式来匹配.
 *
 * 例如
 * [ '/\(张三\)和\(李四\)是好朋友/', 'person', 'person' ] , 会把匹配到的两个参数都加到person里.
 *
 *
 * @property-read string[] $keywords
 *
 * 关键字匹配
 * 每个元素可以是字符串或是数组
 * 如果是字符串, 则字符串之间是 "与" 的关系, 例如 ["必须", "要有", "关键字"]
 * 如果元素本身是数组, 则是 "或" 的关系, 例如 [ ["必须", "必要"], "要有", "关键字"]
 *
 *
 */
class IntentMatcherOption extends Option
{
    public static function stub(): array
    {
        return [
            'signature' => '', //'command {test1}',
            'regex' => [
                //['/pattern1/', 'key1', 'key2', 'key3'],
                //['/pattern2/', 'key1', 'key2', 'key3'],
            ],
            'keywords' => [
                //'a' ,'b', ['synonym1', 'synonym2']
            ],
        ];
    }
}