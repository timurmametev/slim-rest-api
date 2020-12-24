<?php

declare(strict_types=1);

namespace App\Api\v1\dto;

use App\Web\common\dto\BaseDTO;

/**
 * Class ResponseDTO
 * @package App\Api\v1\dto
 *
 * @property $code
 * @property $response
 */
class ResponseDTO extends BaseDTO
{
    protected array $attributeNames = [
        'code',
        'response'
    ];
}