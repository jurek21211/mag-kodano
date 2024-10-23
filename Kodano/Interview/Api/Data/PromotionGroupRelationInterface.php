<?php

namespace Kodano\Interview\Api\Data;

interface PromotionGroupRelationInterface
{
    public const PROMOTION_GROUP_RELATION_TABLE = 'kd_promotions_promotion_group';
    public const ENTITY_ID = 'entity_id';
    public const GROUP_ID = 'group_id';
    public const PROMOTION_ID = 'promotion_id';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getPromotionId(): int;

    /**
     * @param int $promotionId
     * @return PromotionGroupRelationInterface
     */
    public function setPromotionId(int $promotionId): PromotionGroupRelationInterface;

    /**
     * @return int
     */
    public function getGroupId(): int;

    /**
     * @param int $groupId
     * @return PromotionGroupRelationInterface
     */
    public function setGroupId(int $groupId): PromotionGroupRelationInterface;
}
