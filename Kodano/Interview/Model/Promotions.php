<?php
declare(strict_types=1);

namespace Kodano\Interview\Model;

use Kodano\Interview\Api\Data\PromotionsInterface;
use Magento\Framework\Model\AbstractModel;

class Promotions extends AbstractModel implements PromotionsInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(ResourceModel\Promotions::class);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(PromotionsInterface::ENTITY_ID);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(PromotionsInterface::NAME);
    }

    /**
     * @param string $name
     * @return PromotionsInterface
     */
    public function setName(string $name): PromotionsInterface
    {
        $this->setData(PromotionsInterface::NAME, $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(PromotionsInterface::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return PromotionsInterface
     */
    public function setCreatedAt(string $createdAt): PromotionsInterface
    {
        $this->setData(PromotionsInterface::CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return (string)$this->getData(PromotionsInterface::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return PromotionsInterface
     */
    public function setUpdatedAt(string $updatedAt): PromotionsInterface
    {
        $this->setData(PromotionsInterface::UPDATED_AT, $updatedAt);
        return $this;
    }


}
