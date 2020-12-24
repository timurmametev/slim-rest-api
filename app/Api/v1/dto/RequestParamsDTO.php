<?php

declare(strict_types=1);

namespace App\Api\v1\dto;

use App\Web\common\dto\BaseDTO;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;

/**
 * Class RequestParamsDTO
 * @package App\Web\dto
 *
 * @property LazyUuidFromString|string $id
 * @property string $group
 */
class RequestParamsDTO extends BaseDTO
{
    protected array $attributeNames = [
        'id',
        'group',
    ];

    public function processData()
    {
        $this->id = Uuid::isValid((string)$this->id)
            ? Uuid::fromString((string)$this->id)
            : $this->id;
    }
}