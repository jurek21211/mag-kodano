<?php
declare(strict_types=1);

namespace Kodano\Interview\Api;

use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface PromotionGroupRelationRepositoryInterface
{
    /**
     * @param PromotionGroupRelationInterface $object
     * @return PromotionGroupRelationInterface
     */
    public function save(PromotionGroupRelationInterface $object): PromotionGroupRelationInterface;

    /**
     * @param PromotionGroupRelationInterface $object
     * @return void
     */
    public function delete(PromotionGroupRelationInterface $object): void;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param int $promotionId
     * @return array
     */
    public function getByPromotionId(int $promotionId): array;
}
