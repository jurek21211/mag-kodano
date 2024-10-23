<?php
declare(strict_types=1);

namespace Kodano\Interview\Api\Data;

interface PromotionsInterface
{
    public const PROMOTIONS_TABLE = 'kd_promotions';
    public const ENTITY_ID = 'entity_id';
    public const NAME = 'name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return PromotionsInterface
     */
    public function setName(string $name): PromotionsInterface;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return PromotionsInterface
     */
    public function setCreatedAt(string $createdAt): PromotionsInterface;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $updatedAt
     * @return PromotionsInterface
     */
    public function setUpdatedAt(string $updatedAt): PromotionsInterface;
}
