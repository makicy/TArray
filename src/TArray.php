<?php

namespace makicim\tarray;

use ArrayAccess;

class TArray implements ArrayAccess {

    public function __construct(
        public array $contents = [],
        public bool  $isChanged = false
    ) {
    }

    public static function package(mixed $data): mixed {
        return is_array($data) ? new TArray($data) : $data;
    }

    public function all(): array {
        return $this->contents;
    }

    public function map(callable $closure): TArray {
        $this->isChanged = true;
        foreach ($this->contents as $index => $content) $this->contents[$index] = $closure($index, self::package($content));
        return $this;
    }

    public function pop(): TArray {
        $this->isChanged = true;
        $this->delete($this->keyOf(-1));
        return $this;
    }

    public function shift(): TArray {
        $this->isChanged = true;
        $this->delete($this->keyOf(0));
        return $this;
    }

    public function count(): int {
        return count($this->contents);
    }

    public function keys(): TArray {
        return new TArray(array_keys($this->contents));
    }

    public function keyOf(int $index): mixed {
        return $index < 0 ? $this->keyOf($this->count() + $index) : $this->keys()[$index] ?? null;
    }

    public function values(): TArray {
        return new TArray(array_values($this->contents));
    }

    public function merge(): TArray {
        return new TArray(array_merge(...$this->clone()->all()));
    }

    public function unique(): TArray {
        $this->isChanged = true;
        $this->contents = array_unique($this->contents);
        return $this;
    }

    public function clone(): TArray {
        return clone $this;
    }

    public function isset(mixed ...$value): bool {
        return $this->find(...$value) !== null;
    }

    public function end(): mixed {
        return empty($this->contents) ? null : self::package($this->contents[$this->keyOf(-1)]);
    }

    public function first(): mixed {
        return empty($this->contents) ? null : self::package($this->contents[$this->keyOf(0)]);
    }

    private function &scope(mixed ...$value): mixed {
        $current =& $this->contents;
        foreach ($value as $i) {
            if (!isset($current[$i])) $current[$i] = [];
            $current =& $current[$i];
        }

        return $current;
    }

    public function find(mixed ...$value): mixed {
        return self::package($this->scope(...$value));
    }

    public function set(mixed ...$value): TArray {
        $this->isChanged = true;
        $item = array_pop($value);
        $scope = &$this->scope(...$value);
        $scope = $item;
        return $this;
    }

    public function push(mixed ...$value): TArray {
        $this->isChanged = true;
        $item = array_pop($value);
        $scope = &$this->scope(...$value);
        $scope[] = $item;
        return $this;
    }

    public function delete(mixed ...$value): TArray {
        $this->isChanged = true;
        $item = array_pop($value);
        $scope = &$this->scope(...$value);
        unset($scope[$item]);
        return $this;
    }

    public function ascend(): TArray {
        $this->isChanged = true;
        sort($this->contents);
        return $this;
    }

    public function descend(): TArray {
        $this->isChanged = true;
        rsort($this->contents);
        return $this;
    }

    public function keyAscend(): TArray {
        $this->isChanged = true;
        ksort($this->contents);
        return $this;
    }

    public function keyDescend(): TArray {
        $this->isChanged = true;
        krsort($this->contents);
        return $this;
    }

    public function valueAscend(): TArray {
        $this->isChanged = true;
        asort($this->contents);
        return $this;
    }

    public function valueDescend(): TArray {
        $this->isChanged = true;
        arsort($this->contents);
        return $this;
    }

    public function userAscend(callable $closure): TArray {
        $this->isChanged = true;
        uasort($this->contents, $closure);
        return $this;
    }

    public function userDescend(callable $closure): TArray {
        $this->isChanged = true;
        uksort($this->contents, $closure);
        return $this;
    }

    public function nature(): TArray {
        $this->isChanged = true;
        natsort($this->contents);
        return $this;
    }

    public function natureAll(): TArray {
        $this->isChanged = true;
        natcasesort($this->contents);
        return $this;
    }

    public function shuffle(): TArray {
        $this->isChanged = true;
        shuffle($this->contents);
        return $this;
    }

    public function offsetUnset(mixed $offset): void {
        $this->isChanged = true;
        unset($this->contents[$offset]);
    }

    public function offsetExists(mixed $offset): bool {
        return isset($this->contents[$offset]);
    }

    public function offsetGet(mixed $offset): mixed {
        return $this->contents[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        $this->isChanged = true;
        is_null($offset) ? $this->contents[] = $value : $this->contents[$offset] = $value;
    }
}