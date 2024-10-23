<?php
declare(strict_types=1);

namespace Kodano\Interview\Model\ResourceModel;

use Kodano\Interview\Api\Data\PromotionsInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Promotions extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            PromotionsInterface::PROMOTIONS_TABLE,
            PromotionsInterface::ENTITY_ID
        );
    }
}
