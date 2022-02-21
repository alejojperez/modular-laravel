<?php

namespace ModularLaravel\Actions;

interface ReversableAction
{
    public function execute(): bool;

    public function finish(): bool;

    public function message(): string;

    public function rollback(): bool;
}
