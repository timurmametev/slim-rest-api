<?php

declare(strict_types=1);

namespace App\Web\common\collection;

use App\Web\common\dto\DataGetterInterface;

interface CollectionInterface extends DataGetterInterface
{
    public function setData(array $data);
    public function addData(array $data, bool $multiple = false);
}