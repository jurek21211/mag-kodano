<?php
declare(strict_types=1);

namespace Kodano\Interview\Api;

use Kodano\Interview\Api\Data\PromotionsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface PromotionsRepositoryInterface
{
    /**
     * @param PromotionsInterface $object
     * @return PromotionsInterface
     */
    public function save(PromotionsInterface $object): PromotionsInterface;

    /**
     * @param PromotionsInterface $object
     * @return void
     */
    public function delete(PromotionsInterface $object): void;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
