<?php

declare(strict_types=1);

namespace App\Api\v1\dto;

use App\Web\common\dto\BaseDTO;

/**
 * Class ValidateDTO
 * @package App\Api\v1\dto
 *
 * @property array $messages
 */
class ValidateDTO extends BaseDTO
{
    protected array $attributeNames = [
        'messages'
    ];
}