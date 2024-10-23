<?php

namespace Kodano\Interview\Service;

use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Kodano\Interview\Api\Data\PromotionGroupRelationInterfaceFactory;
use Kodano\Interview\Api\Data\PromotionsInterface;
use Kodano\Interview\Api\PromotionGroupRelationRepositoryInterface;
use Kodano\Interview\Api\PromotionGroupRepositoryInterface;
use Kodano\Interview\Api\PromotionGroupServiceInterface;
use Kodano\Interview\Model\PromotionGroupFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class PromotionGroupService implements PromotionGroupServiceInterface
{
    /**
     * @param SerializerInterface $serializer
     * @param PromotionGroupRelationRepositoryInterface $promotionGroupRelationRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PromotionGroupFactory $promotionGroupFactory
     * @param PromotionGroupRepositoryInterface $groupRepository
     * @param PromotionGroupRelationInterfaceFactory $groupRelationFactory
     */
    public function __construct(
        private readonly SerializerInterface                       $serializer,
        private readonly PromotionGroupRelationRepositoryInterface $promotionGroupRelationRepository,
        private readonly SearchCriteriaBuilder                     $searchCriteriaBuilder,
        private readonly PromotionGroupFactory                     $promotionGroupFactory,
        private readonly PromotionGroupRepositoryInterface         $groupRepository,
        private readonly PromotionGroupRelationInterfaceFactory    $groupRelationFactory,
    )
    {

    }


    /**
     * @return string
     */
    public function get(): string
    {
        $groups = $this->getAllGroups();

        $responseArray = [];

        /** @var PromotionGroupInterface $group */
        foreach ($groups as $group) {
            $responseArray[$group->getName()] = [
                'id' => $group->getEntityId(),
                'created_at' => $group->getCreatedAt(),
                'updated_at' => $group->getUpdatedAt()
            ];
        }

        return $this->serializer->serialize($responseArray);
    }


    /**
     * @param string $groupName
     * @return string
     * @throws LocalizedException
     */
    public function add(string $groupName): string
    {
        if (empty($groupName)) {
            throw new LocalizedException(__('Group name cannot be empty.'));
        }

        /** @var PromotionGroupInterface $newGroup */
        $newGroup = $this->promotionGroupFactory->create();

        $newGroup->setName($groupName);
        $newGroup->setCreatedAt(date('Y-m-d H:i:s'));
        $newGroup->setUpdatedAt(date('Y-m-d H:i:s'));

        $this->groupRepository->save($newGroup);

        $response = [
            'promotionName' => $newGroup->getName(),
            'result' => true
        ];

        return $this->serializer->serialize($response);
    }


    /**
     * @param int $groupId
     * @return string
     * @throws LocalizedException
     */
    public function remove(int $groupId): string
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            PromotionGroupInterface::ENTITY_ID,
            $groupId,
            'eq'
        )->create();

        $groups = $this->groupRepository->getList($searchCriteria)->getItems();

        if (!$groups) {
            throw new LocalizedException(__('Group not found.'));
        }

        /** @var PromotionGroupInterface $group */
        $group = array_shift($groups);

        $this->groupRepository->delete($group);

        return 'Removed. ID: ' . $group->getEntityId();
    }

    /**
     * @param int|null $groupId
     * @param int|null $promotionId
     * @return string
     */
    public function addPromotionToGroup(int $groupId = null, int $promotionId = null): string
    {
        $newRelation = $this->groupRelationFactory->create();

        $newRelation->setGroupId($groupId);
        $newRelation->setPromotionId($promotionId);

        $this->promotionGroupRelationRepository->save($newRelation);

        return 'Added new relation. Group ID: ' . $groupId . ' Promotion ID: ' . $promotionId;
    }

    /**
     * @return array
     */
    private function getAllGroups(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $this->groupRepository->getList($searchCriteria)->getItems();
    }
}
