<?php

namespace Kodano\Interview\Model;

use Exception;
use Kodano\Interview\Api\Data\PromotionsInterface;
use Kodano\Interview\Api\PromotionsRepositoryInterface;
use Kodano\Interview\Model\ResourceModel\Promotions;
use Kodano\Interview\Model\ResourceModel\Promotions\Collection;
use Kodano\Interview\Model\ResourceModel\Promotions\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;


class PromotionsRepository implements PromotionsRepositoryInterface
{
    /**
     * @param Promotions $promotionsResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly ResourceModel\Promotions      $promotionsResourceModel,
        private readonly CollectionFactory             $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        private readonly CollectionProcessorInterface  $collectionProcessor
    )
    {

    }

    /**
     * @param PromotionsInterface $object
     * @return PromotionsInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionsInterface $object): PromotionsInterface
    {
        try {
            $this->promotionsResourceModel->save($object);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $object;
    }

    /**
     * @param PromotionsInterface $object
     * @return void
     * @throws Exception
     */
    public function delete(PromotionsInterface $object): void
    {
        $this->promotionsResourceModel->delete($object);
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
