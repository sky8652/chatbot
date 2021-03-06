<?php

/**
 * Class MailMessage
 * @package Commune\Chatbot\Blueprint\Conversation
 */

namespace Commune\Chatbot\Blueprint\Conversation;


use Carbon\Carbon;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Support\Arr\ArrayAndJsonAble;

/**
 * Interface ConversationMessage
 * @package Commune\Chatbot\Blueprint\Conversation
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $id
 * @property-read string $chatId
 * @property-read string $userId
 * @property-read string $traceId
 * @property-read string $platformId
 * @property-read Carbon $createdAt
 * @property-read Message $message
 * @property-read string|null $replyToId
 * @property-read Message|null $replyTo
 *
 *
 */
interface ConversationMessage extends ArrayAndJsonAble
{
    /**
     * 消息ID, 全局唯一
     *
     * @return string
     */
    public function getId() : string;

    /*------- 关键ID -------*/

    /**
     * 用于追踪调用链条的ID
     * 
     * uuid for request
     * request life circle share same trace id
     *
     * @return string
     */
    public function getTraceId() : string;

    /**
     * 用于标记对话的唯一ID.
     *
     * messages with same chat id consider in same chat
     * @return string
     */
    public function getChatId() : string ;

    /**
     * 一次完整的对话的 sessionId. 一个 Chat 可能有若干轮 Session
     * @return null|string
     */
    public function getSessionId() : ? string;

    /**
     * user unique id
     * may be platform user id or system generated id
     * decided by request
     *
     * @return string
     */
    public function getUserId() : string;

    /**
     * unique id for chatbot self
     *
     * @return string
     */
    public function getChatbotName() : string ;

    /**
     * platform which chatbot located
     *
     * @return string
     */
    public function getPlatformId() : string;

    /*------- 状态 -------*/

    public function getCreatedAt() : Carbon;


    /*------- 消息 -------*/

    /**
     * origin message
     *
     * @return Message
     */
    public function getMessage() : Message ;

    /**
     * if is null, means it is incoming message
     * else is reply message
     * @return null|string
     */
    public function getReplyToId() : ? string ;

}