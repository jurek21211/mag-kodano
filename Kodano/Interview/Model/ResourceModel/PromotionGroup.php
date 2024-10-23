<?php
declare(strict_types=1);

namespace Kodano\Interview\Model\ResourceModel;

use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PromotionGroup extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            PromotionGroupInterface::PROMOTION_GROUP_TABLE,
            PromotionGroupInterface::ENTITY_ID
        );
    }
}
