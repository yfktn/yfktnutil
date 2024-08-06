<?php namespace Yfktn\Yfktnutil\Classes\Contracts;

interface TriggerProcessor
{
    public function getTriggerTypeHandler(): string;

    public function getType(): string;

    public function run(array $data): void;
}