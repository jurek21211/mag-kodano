<?php
declare(strict_types=1);

namespace Kodano\Interview\Test\Unit\Service;

use Kodano\Interview\Model\PromotionGroup;
use Kodano\Interview\Service\PromotionGroupService;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use Kodano\Interview\Api\Data\PromotionGroupInterface;
use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Kodano\Interview\Api\Data\PromotionsInterface;
use Kodano\Interview\Api\PromotionGroupRelationRepositoryInterface;
use Kodano\Interview\Api\PromotionGroupRepositoryInterface;
use Kodano\Interview\Api\Data\PromotionGroupRelationInterfaceFactory;
use Kodano\Interview\Model\PromotionGroupFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Serialize\SerializerInterface;

class PromotionGroupServiceTest extends TestCase
{
    private $serializer;
    private $promotionGroupRelationRepository;
    private $searchCriteriaBuilder;
    private $promotionGroupFactory;
    private $groupRepository;
    private $groupRelationFactory;
    private $promotionGroupService;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->promotionGroupRelationRepository = $this->createMock(PromotionGroupRelationRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->promotionGroupFactory = $this->createMock(PromotionGroupFactory::class);
        $this->groupRepository = $this->createMock(PromotionGroupRepositoryInterface::class);
        $this->groupRelationFactory = $this->createMock(PromotionGroupRelationInterfaceFactory::class);

        $this->promotionGroupService = new PromotionGroupService(
            $this->serializer,
            $this->promotionGroupRelationRepository,
            $this->searchCriteriaBuilder,
            $this->promotionGroupFactory,
            $this->groupRepository,
            $this->groupRelationFactory
        );
    }

    /**
     * Data provider for testAddGroup
     *
     * @return array[]
     */
    public function addGroupProvider(): array
    {
        return [
            ['Group 1', 'Group 1', true],
            ['Group 2', 'Group 2', true],
            ['', '', false]
        ];
    }

    /**
     * @dataProvider addGroupProvider
     */
    public function testAdd(string $groupName, string $expectedName, bool $expectedResult): void
    {
        if (empty($groupName)) {
            $this->expectException(LocalizedException::class);
            $this->expectExceptionMessage('Group name cannot be empty.');
        } else {
            $groupMock = $this->createMock(PromotionGroupInterface::class);
            $this->promotionGroupFactory->method('create')->willReturn($groupMock);

            $groupMock->expects($this->once())->method('setName')->with($groupName);
            $groupMock->expects($this->once())->method('setCreatedAt');
            $groupMock->expects($this->once())->method('setUpdatedAt');

            $this->groupRepository->expects($this->once())->method('save')->with($groupMock);

            $expectedResponse = [
                'promotionName' => $groupMock->getName(),
                'result' => $expectedResult
            ];

            $this->serializer->expects($this->once())
                ->method('serialize')
                ->with($expectedResponse)
                ->willReturn(json_encode($expectedResponse));
        }

        $result = $this->promotionGroupService->add($groupName);

        if ($expectedResult) {
            $this->assertEquals(json_encode($expectedResponse), $result);
        }
    }

    /**
     * Data provider for testRemoveGroup
     *
     * @return array[]
     */
    public function removeGroupProvider(): array
    {
        return [
            [1, 'Removed. ID: 1', true],  // Valid case: group exists
            [2, 'Removed. ID: 2', true],  // Valid case: group exists
            [999, 'Group not found.', false]  // Invalid case: group does not exist
        ];
    }

    /**
     * @dataProvider removeGroupProvider
     */
    public function testRemove(int $groupId, string $expectedMessage, bool $groupExists)
    {
        // Mock SearchCriteriaBuilder and SearchCriteriaInterface
        $searchCriteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);
        $this->searchCriteriaBuilder->method('addFilter')->willReturnSelf();
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        if (!$groupExists) {
            // Mock the scenario where no group is found
            $this->groupRepository->method('getList')->with($searchCriteriaMock)->willReturn($this->createMock(SearchResultsInterface::class));
            $this->groupRepository->method('getList')->willReturnCallback(function () {
                $searchResultsMock = $this->createMock(SearchResultsInterface::class);
                $searchResultsMock->method('getItems')->willReturn([]);  // Return an empty array when no group is found
                return $searchResultsMock;
            });

            // Expect an exception for when the group is not found
            $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
            $this->expectExceptionMessage('Group not found.');

            // Call the remove method, which should throw the exception in the failure case
            $this->promotionGroupService->remove($groupId);

        } else {
            // Mock the scenario where the group is found
            $groupMock = $this->createMock(PromotionGroupInterface::class);
            $groupMock->method('getEntityId')->willReturn($groupId);

            $searchResultsMock = $this->createMock(SearchResultsInterface::class);
            $searchResultsMock->method('getItems')->willReturn([$groupMock]);

            // Set up the mock for the repository to return the found group
            $this->groupRepository->method('getList')->with($searchCriteriaMock)->willReturn($searchResultsMock);

            // Expect the delete method to be called on the repository with the group mock
            $this->groupRepository->expects($this->once())
                ->method('delete')
                ->with($groupMock);

            // Call the remove method and assert the result
            $result = $this->promotionGroupService->remove($groupId);
            $this->assertEquals($expectedMessage, $result);
        }
    }

    /**
     * Data provider for testGetGroups
     *
     * @return array[]
     */
    public function getGroupsProvider(): array
    {
        return [
            [
                [
                    ['id' => 1, 'name' => 'Group 1', 'created_at' => '2024-01-01', 'updated_at' => '2024-01-02'],
                    ['id' => 2, 'name' => 'Group 2', 'created_at' => '2024-01-05', 'updated_at' => '2024-01-06']
                ]
            ],
            [
                [
                    ['id' => 3, 'name' => 'Group 3', 'created_at' => '2024-02-01', 'updated_at' => '2024-02-02']
                ]
            ]
        ];
    }

    /**
     * @dataProvider getGroupsProvider
     */
    public function testGet(array $groups)
    {
        // Mock the SearchCriteriaInterface
        $searchCriteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);

        // Mock the return of search criteria from the searchCriteriaBuilder
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        // Mock group data
        $groupMocks = array_map(function ($groupData) {
            $groupMock = $this->createMock(PromotionGroupInterface::class);
            $groupMock->method('getEntityId')->willReturn($groupData['id']);
            $groupMock->method('getName')->willReturn($groupData['name']);
            $groupMock->method('getCreatedAt')->willReturn($groupData['created_at']);
            $groupMock->method('getUpdatedAt')->willReturn($groupData['updated_at']);
            return $groupMock;
        }, $groups);

        // Mock getList to return the search results
        $searchResultsMock = $this->createMock(SearchResultsInterface::class);
        $this->groupRepository->method('getList')->with($searchCriteriaMock)->willReturn($searchResultsMock);
        $searchResultsMock->method('getItems')->willReturn($groupMocks);

        // Prepare expected response array
        $responseArray = [];
        foreach ($groups as $group) {
            $responseArray[$group['name']] = [
                'id' => $group['id'],
                'created_at' => $group['created_at'],
                'updated_at' => $group['updated_at'],
            ];
        }

        // Expect the serializer to serialize the result array
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($responseArray)
            ->willReturn(json_encode($responseArray));

        // Call the get method and assert
        $result = $this->promotionGroupService->get();
        $this->assertEquals(json_encode($responseArray), $result);
    }


    /**
     * Data provider for testAddPromotionToGroup
     *
     * @return array[]
     */
    public function addPromotionToGroupProvider(): array
    {
        return [
            [1, 101, true],
            [2, 202, true]
        ];
    }

    /**
     * @dataProvider addPromotionToGroupProvider
     */
    public function testAddPromotionToGroup(?int $groupId, ?int $promotionId, bool $expectedResult)
    {
        $relationMock = $this->createMock(PromotionGroupRelationInterface::class);
        $this->groupRelationFactory->method('create')->willReturn($relationMock);

        if ($expectedResult) {
            $relationMock->expects($this->once())->method('setGroupId')->with($groupId);
            $relationMock->expects($this->once())->method('setPromotionId')->with($promotionId);

            $this->promotionGroupRelationRepository->expects($this->once())
                ->method('save')
                ->with($relationMock);
        } else {
            $this->promotionGroupRelationRepository->expects($this->never())->method('save');
        }

        $result = $this->promotionGroupService->addPromotionToGroup($groupId, $promotionId);
        $this->assertEquals($expectedResult, !empty($result));
    }
}
