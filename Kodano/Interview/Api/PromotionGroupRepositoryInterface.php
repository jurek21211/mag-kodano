<?php
declare(strict_types=1);

namespace Kodano\Interview\Api;

use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface PromotionGroupRepositoryInterface
{
    /**
     * @param PromotionGroupInterface $object
     * @return PromotionGroupInterface
     */
    public function save(PromotionGroupInterface $object): PromotionGroupInterface;

    /**
     * @param PromotionGroupInterface $object
     * @return void
     */
    public function delete(PromotionGroupInterface $object): void;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
