<?php

declare(strict_types=1);

namespace App\Web\common\dto;

interface DataGetterInterface
{
    public function getData(bool $asArray = true) : array;
}