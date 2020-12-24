<?php

declare(strict_types=1);

namespace App\Web\dto;

use App\Web\common\dto\BaseDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class ConsumerDTO
 * @package App\Web\dto
 *
 * @property ?UuidInterface $id
 * @property ?string $group
 */
class ConsumerDTO extends BaseDTO
{
    protected array $attributeNames = [
        'id',
        'group'
    ];

    public function processData()
    {
        $this->id = ($this->id instanceof UuidInterface)
            ? $this->id->toString()
            : null;
    }
}