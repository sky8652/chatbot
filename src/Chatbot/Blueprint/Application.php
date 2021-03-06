<?php

/**
 * Class ChatbotApp
 * @package Commune\Chatbot\Blueprint
 */

namespace Commune\Chatbot\Blueprint;

use Commune\Chatbot\Blueprint\Conversation\ConversationContainer;
use Commune\Chatbot\Config\ChatbotConfig;
use Commune\Chatbot\Contracts\ChatServer;
use Commune\Chatbot\Contracts\ConsoleLogger;
use Commune\Chatbot\Framework\Exceptions\BootingException;
use Commune\Container\ContainerContract;
use Commune\Chatbot\Blueprint\Conversation\Conversation;

/**
 * Commune Chat application
 */
interface Application
{

    /*----------- 预绑定组件 ------------*/

    /**
     * 启动时, 系统专用的日志模块.
     * 在console 里打印日志.
     *
     * @return ConsoleLogger
     */
    public function getConsoleLogger(): ConsoleLogger;

    /**
     * 系统默认的日志.
     *
     * @return ChatbotConfig
     */
    public function getConfig(): ChatbotConfig;

    /*----------- container ------------*/

    /**
     * 进程级别的 IoC 容器
     * @return ContainerContract
     */
    public function getProcessContainer() : ContainerContract;

    /**
     * Conversation 的 IoC 容器
     * @return ConversationContainer
     */
    public function getConversationContainer() : ConversationContainer;


    /*----------- 注册 ------------*/

    /**
     * 启动 worker 进程的预加载环节. 在进入请求之前必须执行.
     * 会运行所有的 bootstrapper
     * 并加载所有的 service provider 的 register 方法.
     *
     * boot app at worker process, before any request
     *
     * @throws BootingException
     */
    public function bootApp() : Application;

    /**
     * 最高优先级的服务注册.
     * 本质上还是 process service provider, 但会先于其他 process service 执行.
     * @param $provider
     */
    public function registerConfigService($provider) : void;

    /**
     * 使用 ServiceProvider 注册进程级的服务
     * @param string|ServiceProvider $provider
     */
    public function registerProcessService($provider) : void;

    /**
     * 使用 ServiceProvider 注册 Conversation 的服务
     * @param string|ServiceProvider $provider
     */
    public function registerConversationService($provider) : void;

    /**
     * 初始化 Conversation 注册的服务, 调用 service provider 的 boot 方法.
     * 理论上每个请求都要执行一遍
     *
     * @param Conversation $conversation
     */
    public function bootConversation(Conversation $conversation) : void;

    /*----------- 运行时必要的服务 ------------*/

    /**
     * 获取系统的 kernel
     * @return ChatKernel
     */
    public function getKernel() : ChatKernel;

    /**
     * 获取 commune chatbot 定义的 server
     * @return ChatServer
     */
    public function getServer() : ChatServer;

}