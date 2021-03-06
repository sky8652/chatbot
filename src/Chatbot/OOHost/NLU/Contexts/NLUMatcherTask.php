<?php

namespace Commune\Chatbot\OOHost\NLU\Contexts;

use Commune\Chatbot\App\Contexts\TaskDef;
use Commune\Chatbot\OOHost\Context\Depending;
use Commune\Chatbot\OOHost\Context\Exiting;
use Commune\Chatbot\OOHost\Context\Stage;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\OOHost\Directing\Navigator;

/**
 * NLU 管理工具之一.
 * 查看 NLU 命中的意图.
 */
class NLUMatcherTask extends TaskDef
{
    const DESCRIPTION = '查看NLU命中的意图';


    public static function __depend(Depending $depending): void
    {
    }

    public static function getContextName(): string
    {
        return 'nlu.component.matcher';
    }

    /**
     * 非管理员也允许访问
     * @param Stage $stage
     */
    public function __staging(Stage $stage) : void
    {
        // $stage->onStart(new MustBeSupervisor());
    }

    public function navigate(Dialog $dialog): ? Navigator
    {
        return $dialog->redirect->sleepTo($this);
    }

    public function __onStart(Stage $stage): Navigator
    {
        return $stage->buildTalk()
            ->info(
                "进入NLU意图管理. 
请输入任意语句, 会给出命中的意图. 
输入'b'退出工具"
            )
            ->wait()
            ->hearing()
            ->is('b', function(Dialog $dialog){
                return $dialog->fulfill();
            })
            ->end(function(Dialog $dialog){

                $dialog->say()
                    ->info("当前匹配结果如下:")
                    ->info($dialog->session->nlu->toPrettyJson());

                return $dialog->repeat();
            });
    }

    public function __exiting(Exiting $listener): void
    {
    }


}