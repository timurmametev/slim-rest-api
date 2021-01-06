<?php

declare(strict_types=1);

namespace App\Api\v1\dto;

use App\Web\common\dto\BaseDTO;

/**
 * Class ValidateErrorDTO
 * @package App\Api\v1\dto
 *
 * @property string $parameter
 * @property string $message
 */
class ValidateErrorDTO extends BaseDTO
{
    protected array $attributeNames = [
        'parameter',
        'message'
    ];
}