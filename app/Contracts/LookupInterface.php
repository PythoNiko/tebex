<?php

namespace App\Contracts;

interface LookupInterface
{
    public function supports(string $type): bool;
    public function lookup(array $params): array;
}
