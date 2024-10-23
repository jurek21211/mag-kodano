<?php
declare(strict_types=1);

namespace Kodano\Interview\Model;

use Exception;
use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Kodano\Interview\Api\PromotionGroupRelationRepositoryInterface;
use Kodano\Interview\Model\ResourceModel\PromotionGroupRelation\Collection;
use Kodano\Interview\Model\ResourceModel\PromotionGroupRelation\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class PromotionGroupRelationRepository implements PromotionGroupRelationRepositoryInterface
{
    /**
     * @param ResourceModel\PromotionGroupRelation $promotionGroupRelationResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsInterfaceFactory
     */
    public function __construct(
        private readonly ResourceModel\PromotionGroupRelation $promotionGroupRelationResourceModel,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {

    }

    /**
     * @param PromotionGroupRelationInterface $object
     * @return PromotionGroupRelationInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionGroupRelationInterface $object): PromotionGroupRelationInterface
    {
        try {
            $this->promotionGroupRelationResourceModel->save($object);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $object;
    }

    /**
     * @param PromotionGroupRelationInterface $object
     * @return void
     * @throws Exception
     */
    public function delete(PromotionGroupRelationInterface $object): void
    {
       $this->promotionGroupRelationResourceModel->delete($object);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param int $promotionId
     * @return array
     */
    public function getByPromotionId(int $promotionId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            PromotionGroupRelationInterface::PROMOTION_ID,
            $promotionId,
            'eq'
        )->create();

        return $this->getList($searchCriteria)->getItems();
    }
}
