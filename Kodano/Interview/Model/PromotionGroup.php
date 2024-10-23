<?php

namespace Kodano\Interview\Model;

use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Magento\Framework\Model\AbstractModel;

class PromotionGroup extends AbstractModel implements PromotionGroupInterface
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(ResourceModel\PromotionGroup::class);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(PromotionGroupInterface::ENTITY_ID);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData(PromotionGroupInterface::NAME);
    }

    /**
     * @param string $name
     * @return PromotionGroupInterface
     */
    public function setName(string $name): PromotionGroupInterface
    {
        $this->setData(PromotionGroupInterface::NAME, $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(PromotionGroupInterface::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return PromotionGroupInterface
     */
    public function setCreatedAt(string $createdAt): PromotionGroupInterface
    {
        $this->setData(PromotionGroupInterface::CREATED_AT, $createdAt);

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData(PromotionGroupInterface::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return PromotionGroupInterface
     */
    public function setUpdatedAt(string $updatedAt): PromotionGroupInterface
    {
        $this->setData(PromotionGroupInterface::UPDATED_AT, $updatedAt);

        return $this;
    }

}
