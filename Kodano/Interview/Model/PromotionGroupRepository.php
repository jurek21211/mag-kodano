<?php
declare(strict_types=1);

namespace Kodano\Interview\Model;

use Exception;
use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Kodano\Interview\Api\PromotionGroupRepositoryInterface;
use Kodano\Interview\Model\ResourceModel\PromotionGroup;
use Kodano\Interview\Model\ResourceModel\PromotionGroup\Collection;
use Kodano\Interview\Model\ResourceModel\PromotionGroup\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class PromotionGroupRepository implements PromotionGroupRepositoryInterface
{
    /**
     * @param PromotionGroup $promotionGroupResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsInterfaceFactory
     */
    public function __construct(
        private readonly ResourceModel\PromotionGroup $promotionGroupResourceModel,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {

    }

    /**
     * @param PromotionGroupInterface $object
     * @return PromotionGroupInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionGroupInterface $object): PromotionGroupInterface
    {
        try {
            $this->promotionGroupResourceModel->save($object);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $object;
    }

    /**
     * @param PromotionGroupInterface $object
     * @return void
     * @throws Exception
     */
    public function delete(PromotionGroupInterface $object): void
    {
        $this->promotionGroupResourceModel->delete($object);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
