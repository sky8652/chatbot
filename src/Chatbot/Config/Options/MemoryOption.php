<?php


namespace Commune\Chatbot\Config\Options;


use Commune\Chatbot\OOHost\Session\Scope;
use Commune\Support\Option;

/**
 * 在 Session 中定义 memory
 *
 * @property-read string $name
 * @property-read string $desc
 * @property-read string[] $scopes
 * @property-read string[] $entities
 */
class MemoryOption extends Option
{
    const IDENTITY = 'name';

    public static function stub(): array
    {
        return [
            'name' => 'sandbox',
            'desc' => 'description',
            'scopes' => [Scope::SESSION_ID],
            'entities' => [
                'test'
            ]
        ];
    }

    public static function validate(array $data): ? string
    {
        if (empty($data['name']) || !is_string($data['name'])) {
            return 'field name is not valid';
        }

        if (empty($data['desc']) || !is_string($data['desc'])) {
            return 'field desc is not valid';
        }

        if (empty($data['scopes']) || !is_array($data['scopes'])) {
            return 'field scopes is invalid';
        }

        return null;
    }

}