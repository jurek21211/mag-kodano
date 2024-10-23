<?php
declare(strict_types=1);

namespace Kodano\Interview\Model\ResourceModel;


use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PromotionGroupRelation extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            PromotionGroupRelationInterface::PROMOTION_GROUP_RELATION_TABLE,
            PromotionGroupRelationInterface::ENTITY_ID
        );
    }
}

