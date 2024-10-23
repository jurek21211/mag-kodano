<?php

namespace Kodano\Interview\Api\Data;

interface PromotionGroupInterface
{
    public const PROMOTION_GROUP_TABLE = 'kd_promotion_group';
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
     * @return PromotionGroupInterface
     */
    public function setName(string $name): PromotionGroupInterface;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return PromotionGroupInterface
     */
    public function setCreatedAt(string $createdAt): PromotionGroupInterface;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return PromotionGroupInterface
     */
    public function setUpdatedAt(string $updatedAt): PromotionGroupInterface;
}
