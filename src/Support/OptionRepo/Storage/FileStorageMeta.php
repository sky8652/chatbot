<?php


namespace Commune\Support\OptionRepo\Storage;


use Commune\Support\Option;
use Commune\Support\OptionRepo\Options\StorageMeta;


/**
 * @property-read string $name storage 名
 * @property-read string $path 当前配置存储的文件. 一个文件里只应该有一种option
 * @property-read bool $isDir path是文件名, 还是路径名. 如果是路径名, 会用option id 做文件名.
 * @property-read int|int[]|string|string[] $depth 目录搜索的深度. @see Finder::depth()
 */
abstract class FileStorageMeta extends StorageMeta
{
    public static function stub(): array
    {
        return [
            'name' => static::class,
            'path' => '',
            'isDir' => true,
            'depth' => 0,
        ];
    }

    public static function validate(array $data): ? string
    {
        if (empty($data['name'])) {
            return 'name field is required';
        }

        if (empty($data['path'])) {
            return 'path field is required';
        }

        $path = $data['path'];
        $dir = $data['isDir'] ?? false;

        if (!is_bool($dir)) {
            return 'isDir should be bool value';
        }

        if ($dir && !is_dir($path)) {
            return "resource directory path $path not exits";

        } elseif (!$dir && !file_exists($path)) {
            return "resource file $path not exits";

        }

        return parent::validate($data);
    }


    public function newOption(string $className, array $data, string $path) : Option
    {
        return new $className($data);
    }

}