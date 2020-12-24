<?php

declare(strict_types=1);

namespace App\Web\common\dto;

Interface DTOInterface extends DataGetterInterface
{
    public function __construct(array $attributes = []);
    public function processData();
}