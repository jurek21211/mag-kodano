<?php

namespace Kodano\Interview\Service;

use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Kodano\Interview\Api\Data\PromotionsInterface;
use Kodano\Interview\Api\PromotionGroupRelationRepositoryInterface;
use Kodano\Interview\Api\PromotionServiceInterface;
use Kodano\Interview\Api\PromotionsRepositoryInterface;
use Kodano\Interview\Model\PromotionsFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class PromotionService implements PromotionServiceInterface
{
    /**
     * @param SerializerInterface $serializer
     * @param PromotionsRepositoryInterface $promotionsRepository
     * @param PromotionGroupRelationRepositoryInterface $promotionGroupRelationRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PromotionsFactory $promotionsFactory
     */
    public function __construct(
        private readonly SerializerInterface                       $serializer,
        private readonly PromotionsRepositoryInterface             $promotionsRepository,
        private readonly PromotionGroupRelationRepositoryInterface $promotionGroupRelationRepository,
        private readonly SearchCriteriaBuilder                     $searchCriteriaBuilder,
        private readonly PromotionsFactory                         $promotionsFactory
    )
    {

    }

    /**
     * @return string
     */
    public function get(): string
    {
        $promotions = $this->getAllPromotions();
        $responseArray = [];

        foreach ($promotions as $promotion) {
            $responseArray[$promotion->getName()] = $this->buildPromotionResponse($promotion);
        }

        return $this->serializer->serialize($responseArray);
    }

    /**
     * Get all promotions from the repository.
     *
     * @return PromotionsInterface[]
     */
    private function getAllPromotions(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->promotionsRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Build the response array for a promotion.
     *
     * @param PromotionsInterface $promotion
     * @return array
     */
    private function buildPromotionResponse(PromotionsInterface $promotion): array
    {
        return [
            'id' => $promotion->getEntityId(),
            'groups' => $this->getPromotionGroupIds($promotion->getEntityId()),
            'created_at' => $promotion->getCreatedAt(),
            'updated_at' => $promotion->getUpdatedAt()
        ];
    }

    /**
     * Get the group IDs for a specific promotion.
     *
     * @param int $promotionId
     * @return array
     */
    private function getPromotionGroupIds(int $promotionId): array
    {
        $groups = $this->promotionGroupRelationRepository->getByPromotionId($promotionId);

        return array_values(array_map(fn($group) => $group->getGroupId(), $groups));
    }


    /**
     * @param int $promotionId
     * @return string
     * @throws LocalizedException
     */
    public function remove(int $promotionId): string
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            PromotionsInterface::ENTITY_ID,
            $promotionId,
            'eq'
        )->create();

        $promotions = $this->promotionsRepository->getList($searchCriteria)->getItems();

        if (!$promotions) {
            throw new LocalizedException(__('Promotion not found.'));
        }

        /** @var PromotionsInterface $promotion */
        $promotion = array_shift($promotions);

        $this->promotionsRepository->delete($promotion);

        return 'Removed. ID: ' . $promotion->getEntityId();
    }

    /**
     * @param string $promotionName
     * @return string
     * @throws LocalizedException
     */
    public function add(string $promotionName): string
    {
        if (empty($promotionName)) {
            throw new LocalizedException(__('Promotion name cannot be empty.'));
        }

        /** @var PromotionsInterface $newPromotion */
        $newPromotion = $this->promotionsFactory->create();

        $newPromotion->setName($promotionName);
        $newPromotion->setCreatedAt(date('Y-m-d H:i:s'));
        $newPromotion->setUpdatedAt(date('Y-m-d H:i:s'));

        $this->promotionsRepository->save($newPromotion);

        $response = [
            'promotionName' => $newPromotion->getName(),
            'result' => true
        ];

        return $this->serializer->serialize($response);
    }
}
