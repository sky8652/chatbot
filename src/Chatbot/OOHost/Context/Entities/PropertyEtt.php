<?php


namespace Commune\Chatbot\OOHost\Context\Entities;

use Commune\Chatbot\App\Messages\QA\VbQuestion;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\Blueprint\Message\QA\Answer;
use Commune\Chatbot\Framework\Exceptions\ChatbotLogicException;
use Commune\Chatbot\OOHost\Context\Definition;
use Commune\Chatbot\OOHost\Context\Intent\IntentMessage;
use Commune\Chatbot\OOHost\Context\Stage;
use Commune\Chatbot\OOHost\Context\Context;
use Commune\Chatbot\OOHost\Context\Entity;
use Commune\Chatbot\OOHost\Context\Memory\Memory;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\OOHost\Directing\Navigator;

/**
 * 默认的 entity, 可以与 memory 模块结合.
 */
class PropertyEtt implements Entity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $memoryName;

    /**
     * @var string
     */
    protected $memoryKey;

    /**
     * @var mixed
     */
    protected $default;


    /**
     * @var bool
     */
    protected $isOptional;

    /**
     * @var string
     */
    protected $question;

    /**
     * @var callable|null
     */
    protected $validator;

    /**
     * AbsEntity constructor.
     * @param string $name
     * @param string $question
     * @param null $default
     * @param string $memoryName
     * @param string|null $memoryKey
     */
    public function __construct(
        string $name,
        string $question = '',
        $default = null,
        string $memoryName = '',
        string $memoryKey = null
    )
    {
        $this->name = $name;
        $this->question = $question;
        $this->default = $default;
        $this->memoryName = $memoryName;
        $this->memoryKey = !empty($memoryKey) ? $memoryKey : $this->name;
        $this->isOptional = isset($default);
    }


    public function asStage(Stage $stageRoute) : Navigator
    {
        // 如果数据存在, 则走下一步.
        $value = $stageRoute->self->{$this->name};
        if (isset($value)) {
            return $stageRoute->dialog->next();
        }

        // context 定义的 stage 最高优.
        // stage method 存在的时候, stage 使用该方法.
        $stageMethod = Context::STAGE_METHOD_PREFIX . $this->name;
        if (method_exists($stageRoute->self, $stageMethod)) {
            return $stageRoute->self->{$stageMethod}($stageRoute);
        }

        // 如果是记忆的话, 用记忆的stage 取代当前的stage
        if (!empty($this->memoryName)) {
            return $stageRoute->dependOn(
                $this->memoryName,
                null,
                [$this->memoryKey]
            );
        }

        // 默认用entity 自己的 stage 方法
        return $stageRoute
            ->onStart(function(Context $self, Dialog $dialog) {
                $this->askDefaultQuestion($self, $dialog);
                return $dialog->wait();
            })
            ->wait([$this, 'defaultCallback']);
    }


    public function defaultCallback(Dialog $dialog, Message $message) : Navigator
    {
        return $dialog->hear($message)
            // 如果得到了答案
            ->isAnswer(function(Context $self, Dialog $dialog, Answer $answer) {

                $result = $this->validate($self, $dialog, $answer);
                // 不为null 表示校验失败.
                if (isset($result)) {
                    return $result;
                }

                // 赋值
                $this->set($self, $answer->toResult());
                // 进入下一步.
                return $dialog->next();
            })

            // 没有答案的情况.
            ->end(function(Dialog $dialog) : Navigator {
                return $this->defaultBadAnswer($dialog);
            });

    }

    protected function validate(Context $self, Dialog $dialog, Message $message) : ? Navigator
    {
        // 用interceptor 作为一种校验方式.
        if (isset($this->validator)) {
            return $dialog->app
                ->callContextInterceptor($self, $this->validator, $message);
        }

        // 不做任何校验.
        return null;
    }


    protected function defaultBadAnswer(Dialog $dialog) : Navigator
    {
        $dialog->say($this->getSlots())->warning('errors.badAnswer');
        return $dialog->rewind();
    }


    /**
     * 选择太多了一点都不好. 删掉了callable 等提问方式.
     *
     * @param Context $self
     * @param Dialog $dialog
     */
    protected function askDefaultQuestion(Context $self, Dialog $dialog) : void
    {
        // 如果是 intent, 直接请求 entity, 拿到结果会自动赋值, 从而跳过这一步.
        if ($self instanceof IntentMessage) {
            $dialog->say($this->getSlots())->askIntentEntity(
                $this->question,
                $self,
                $this->name,
                $this->default
            );
            return;
        }

        $question = empty($this->question) ? 'ask.entity' : $this->question;

        // 如果都没有, question 是个字符串, 就只好用默认提问.
        $dialog->say($this->getSlots())->ask(new VbQuestion(
            $question,
            [],
            null
        ));
    }

    protected function getSlots() : array
    {
        return [
            'entity_default' => $this->default,
            'entity_name' => $this->name,
        ];
    }

    public function withValidator(callable $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function set(Context $self, $value): void
    {
        if (!empty($this->memoryName)) {
            $this->setMemoryVal($self, $value);
            return;
        }
        $self->setAttribute($this->name, $value);
    }

    public function get(Context $self)
    {
        if (!empty($this->memoryName)) {
            return $this->getMemoryVal($self);
        }

        return $self->getAttribute($this->name) ?? $this->default;
    }

    public function isPrepared(Context $self): bool
    {
        if (!empty($this->memoryName)) {
            return $this->memoryExists($self);
        }
        return $self->hasAttribute($this->name) || $this->isOptional;
    }

    protected function getMemoryDef(Context $self) : Definition
    {
        return $self->getSession()
            ->memoryRepo
            ->getDef($this->memoryName);
    }

    protected function getMemoryObj(Context $self) : Memory
    {
        /**
         * @var Memory $memory
         */
        $memoryDef = $this->getMemoryDef($self);

        if (!isset($memoryDef)) {
            throw new ChatbotLogicException(
                'context '
                . $self->getName()
                . ' define entity '
                . $this->name
                . ' with memory '
                . $this->memoryName
                . ' which is not defined'
            );
        }

        $memory = $memoryDef->newContext();
        return $memory->toInstance($self->getSession());
    }

    protected function setMemoryVal(Context $self, $value): void
    {
        $memory = $this->getMemoryObj($self);
        $memory->__set($this->memoryKey, $value);
    }

    protected function getMemoryVal(Context $self)
    {
        $memory = $this->getMemoryObj($self);
        return $memory->__get($this->memoryKey) ?? null;
    }

    protected function memoryExists(Context $self): bool
    {
        $memory = $this->getMemoryObj($self);
        return $memory->__isset($this->memoryKey);
    }


}