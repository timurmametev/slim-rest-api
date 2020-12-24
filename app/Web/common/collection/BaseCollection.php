<?php

declare(strict_types=1);

namespace App\Web\common\collection;

use App\Web\common\dto\DataGetterInterface;
use App\Web\common\dto\DTOInterface;

abstract class BaseCollection implements CollectionInterface
{
    /**
     * @var array
     */
    protected array $_data = [];

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->_data = $data;
    }

    /**
     * @param bool $asArray
     * @return array
     */
    public function getData(bool $asArray = true): array
    {
        $result = [];
        if ($asArray) {
            foreach ($this->_data as $item) {
                if ($item instanceof DataGetterInterface) {
                    $result[] = $item->getData();
                }
            }
        } else {
            $result = $this->_data;
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->_data);
    }

    /**
     * @param array $data
     */
    protected function addDataMultiple(array $data): void
    {
        $this->_data = array_merge($this->_data, $data);
    }

    /**
     * @param DTOInterface $data
     */
    protected function addDataSingle(DTOInterface $data): void
    {
        $this->_data[] = $data;
    }

}