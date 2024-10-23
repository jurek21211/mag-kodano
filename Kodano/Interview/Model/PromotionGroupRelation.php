<?php

namespace Kodano\Interview\Model;

use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Magento\Framework\Model\AbstractModel;

class PromotionGroupRelation extends AbstractModel implements PromotionGroupRelationInterface
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(ResourceModel\PromotionGroupRelation::class);
    }

    public function getEntityId(): int
    {
        return (int)$this->getData(PromotionGroupRelationInterface::ENTITY_ID);
    }

    public function getPromotionId(): int
    {
        return (int)$this->getData(PromotionGroupRelationInterface::PROMOTION_ID);
    }

    public function setPromotionId(int $promotionId): PromotionGroupRelationInterface
    {
        $this->setData(PromotionGroupRelationInterface::PROMOTION_ID, $promotionId);

        return $this;
    }

    public function getGroupId(): int
    {
        return (int)$this->getData(PromotionGroupRelationInterface::GROUP_ID);
    }

    public function setGroupId(int $groupId): PromotionGroupRelationInterface
    {
        $this->setData(PromotionGroupRelationInterface::GROUP_ID, $groupId);

        return $this;
    }
}
