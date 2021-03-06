<?php

namespace Commune\Chatbot\Blueprint\Message;

use Commune\Chatbot\Blueprint\Conversation\Speech;

/**
 * 纯文本类型的消息.
 * 可以用于文本或语音.
 * 每一组消息有一个 level.
 * 不同level 的消息, 允许用不同的方式发送
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface VerbalMsg extends ConvoMsg
{
    //默认的消息级别
    const DEBUG = Speech::DEBUG;
    const INFO = Speech::INFO;
    const NOTICE = Speech::NOTICE;
    const WARN = Speech::WARNING;
    const ERROR = Speech::ERROR;

    /*--------- 链式调用 ---------*/

    /**
     * 消息的级别.
     * @param string $level
     * @return static
     */
    public function withLevel(string $level);

    /*--------- 获取参数 ---------*/

    /**
     * @return string
     */
    public function getLevel() : string;
}