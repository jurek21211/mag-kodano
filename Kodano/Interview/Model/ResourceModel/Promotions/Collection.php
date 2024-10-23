<?php
declare(strict_types=1);

namespace Kodano\Interview\Model\ResourceModel\Promotions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            \Kodano\Interview\Model\Promotions::class,
            \Kodano\Interview\Model\ResourceModel\Promotions::class
        );
    }
}
