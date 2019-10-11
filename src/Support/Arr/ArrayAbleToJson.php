<?php

namespace Commune\Support\Arr;


/**
 * 对象转数组, 转json的简单封装.
 *
 * Easy way for Arrayable to serialize to json
 * Trait ArrayAbleToJson
 */
trait ArrayAbleToJson
{
    public function toPrettyJson() : string
    {
        return $this->toJson(
            static::PRETTY_JSON
        );
    }

    abstract public function toArray() : array;

    /**
     * implements Jsonable
     *
     * @param int $option
     * @return string
     */
    public function toJson($option = 0 ) : string
    {
        $result = json_encode($this->toArray(), $option);
        if ($result === false) {
            // 想个办法可以记录日志.
            return '';
        }
        return $result;
    }

    public function toString() : string
    {
        return $this->toJson();
    }

    /**
     * implements JsonSerialize
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __toString()
    {
        return $this->toString();
    }

    protected function recursiveToArray(array $data) : array
    {
        $results = [];

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $results[$key] = $value;

            } elseif ($value instanceof ArrayAndJsonAble) {
                $results[$key] = $value->toArray();

            } elseif (is_array($value)) {
                $results[$key] = $this->recursiveToArray($value);

            } elseif (
                is_object($value)
                && method_exists($value, 'toArray')
            ) {
                $results[$key] = $value->toArray();

            } else {
                $results[$key] = $value;
            }
        }

        return $results;
    }
}