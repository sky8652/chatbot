<?php

/**
 * Class SoundLike
 * @package Commune\Support\SoundLike
 */

namespace Commune\Support\SoundLike;


interface SoundLikeInterface
{
    const ZH = 'zh';

    // 精确匹配
    const COMPARE_EXACTLY = 0;
    // 开头一致
    const COMPARE_START_WITH = 1;
    // 结尾一致
    const COMPARE_END_WITH = 2;
    // 任何位置重复
    const COMPARE_ANY_PART = 3;

    public function registerParser(string $lang, SoundLikeParser $parser) : void;

    public function soundLike(
        string $input,
        string $expect,
        int $compareType = self::COMPARE_EXACTLY,
        string $lang = self::ZH
    ) : bool;

}