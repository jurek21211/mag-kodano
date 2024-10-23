<?php
declare(strict_types=1);

namespace Kodano\Interview\Test\Unit\Service;

use Kodano\Interview\Api\Data\PromotionGroupRelationInterface;
use Kodano\Interview\Service\PromotionService;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use Kodano\Interview\Api\Data\PromotionsInterface;
use Kodano\Interview\Api\PromotionGroupRelationRepositoryInterface;
use Kodano\Interview\Api\PromotionsRepositoryInterface;
use Kodano\Interview\Model\PromotionsFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Serialize\SerializerInterface;

class PromotionServiceTest extends TestCase
{
    private $serializer;
    private $promotionsRepository;
    private $promotionGroupRelationRepository;
    private $searchCriteriaBuilder;
    private $promotionsFactory;
    private $promotionService;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->promotionsRepository = $this->createMock(PromotionsRepositoryInterface::class);
        $this->promotionGroupRelationRepository = $this->createMock(PromotionGroupRelationRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);  // Correctly mocked
        $this->promotionsFactory = $this->createMock(PromotionsFactory::class);

        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);  // Return the mock for create()

        $this->promotionService = new PromotionService(
            $this->serializer,
            $this->promotionsRepository,
            $this->promotionGroupRelationRepository,
            $this->searchCriteriaBuilder,
            $this->promotionsFactory
        );
    }

    /**
     * Data provider for testAdd
     *
     * @return array[]
     */
    public function addPromotionProvider(): array
    {
        return [
            ['Promotion 1', 'Promotion 1', true],
            ['Promotion 2', 'Promotion 2', true],
            ['', '', false],  // Empty promotion name should not work
        ];
    }

    /**
     * @dataProvider addPromotionProvider
     * @throws LocalizedException
     */
    public function testAdd($promotionName, $expectedName, $expectedResult): void
    {
        if ($expectedResult === false) {
            // Expect an exception for empty promotion name
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Promotion name cannot be empty.');
    } else {
        // Set up mocks for successful case
        $promotionMock = $this->createMock(PromotionsInterface::class);
        $this->promotionsFactory->method('create')->willReturn($promotionMock);

        $promotionMock->expects($this->once())->method('setName')->with($promotionName);
        $promotionMock->expects($this->once())->method('setCreatedAt');
        $promotionMock->expects($this->once())->method('setUpdatedAt');

        $this->promotionsRepository->expects($this->once())->method('save')->with($promotionMock);

        $expectedResponse = [
            'promotionName' => $promotionMock->getName(),
            'result' => $expectedResult
        ];

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($expectedResponse)
            ->willReturn(json_encode($expectedResponse));
    }

        // Call the add method and assert the result or exception
        if ($expectedResult) {
            // For successful case
            $result = $this->promotionService->add($promotionName);
            $this->assertEquals(json_encode($expectedResponse), $result);
        } else {
            // For the empty string case, this will trigger the exception
            $this->promotionService->add($promotionName);
        }
    }

    /**
     * Data provider for testRemove
     *
     * @return array[]
     */
    public function removePromotionProvider(): array
    {
        return [
            [1, 'Removed. ID: 1', true],
            [2, 'Removed. ID: 2', true],
            [3, 'Removed. ID: 3', true],
            [999, 'Promotion not found.', false]  // Promotion not found case
        ];
    }

    /**
     * @dataProvider removePromotionProvider
     */
    public function testRemove(int $promotionId, string $expectedMessage, bool $promotionExists)
    {
        // Mock SearchCriteriaBuilder and SearchCriteriaInterface
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);
        $this->searchCriteriaBuilder->method('addFilter')->willReturnSelf();
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        if (!$promotionExists) {
            // Mock the scenario where no promotions are found
            $this->promotionsRepository->method('getList')->with($searchCriteriaMock)->willReturn($this->createMock(SearchResultsInterface::class));
            $this->promotionsRepository->method('getList')->willReturnCallback(function () {
                $searchResultsMock = $this->createMock(SearchResultsInterface::class);
                $searchResultsMock->method('getItems')->willReturn([]);  // Return an empty array when no promotion is found
                return $searchResultsMock;
            });

            // Expect an exception for when the promotion is not found
            $this->expectException(LocalizedException::class);
            $this->expectExceptionMessage('Promotion not found.');

            // Call the remove method, which should throw the exception in the failure case
            $this->promotionService->remove($promotionId);

        } else {
            // Mock the scenario where the promotion is found
            $promotionMock = $this->createMock(PromotionsInterface::class);
            $promotionMock->method('getEntityId')->willReturn($promotionId);

            $searchResultsMock = $this->createMock(SearchResultsInterface::class);
            $searchResultsMock->method('getItems')->willReturn([$promotionMock]);

            // Set up the mock for the repository to return the found promotion
            $this->promotionsRepository->method('getList')->with($searchCriteriaMock)->willReturn($searchResultsMock);

            // Expect the delete method to be called on the repository with the promotion mock
            $this->promotionsRepository->expects($this->once())
                ->method('delete')
                ->with($promotionMock);

            // Call the remove method and assert the result
            $result = $this->promotionService->remove($promotionId);
            $this->assertEquals($expectedMessage, $result);
        }
    }

    /**
     * @dataProvider getPromotionsProvider
     */
    public function testGet(array $promotionData)
    {
        // Set up promotion mocks
        $promotionMocks = [];
        foreach ($promotionData as $data) {
            $promotionMock = $this->createMock(PromotionsInterface::class);
            $promotionMock->method('getEntityId')->willReturn($data['id']);
            $promotionMock->method('getName')->willReturn($data['name']);
            $promotionMock->method('getCreatedAt')->willReturn($data['created_at']);
            $promotionMock->method('getUpdatedAt')->willReturn($data['updated_at']);
            $promotionMocks[] = $promotionMock;
        }

        // Set up the group relation mocks
        $groupRelationMocks = [];
        foreach ($promotionData as $data) {
            foreach ($data['groups'] as $groupId) {
                $groupRelationMock = $this->createMock(PromotionGroupRelationInterface::class);
                $groupRelationMock->method('getGroupId')->willReturn($groupId);
                $groupRelationMocks[] = $groupRelationMock;
            }
        }

        // Mock the promotions repository
        $this->promotionsRepository->method('getList')->willReturn($this->createMock(\Magento\Framework\Api\SearchResultsInterface::class));
        $this->promotionsRepository->getList($this->searchCriteriaBuilder->create())->method('getItems')->willReturn($promotionMocks);

        // Mock the promotion group relation repository
        $this->promotionGroupRelationRepository->method('getByPromotionId')->willReturn($groupRelationMocks);

        // Prepare expected response
        $expectedResponse = [];
        foreach ($promotionData as $data) {
            $expectedResponse[$data['name']] = [
                'id' => $data['id'],
                'groups' => $data['groups'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ];
        }

        // Mock the serializer to return expected JSON
        $this->serializer->method('serialize')->willReturn(json_encode($expectedResponse));

        // Call the get method
        $result = $this->promotionService->get();

        // Assert the result
        $this->assertEquals(json_encode($expectedResponse), $result);
    }

    /**
     * @return array[]
     */
    public function getPromotionsProvider(): array
    {
        return [
            [
                [
                    ['id' => 1, 'name' => 'Promotion 1', 'created_at' => '2024-01-01', 'updated_at' => '2024-01-02', 'groups' => [101, 102]],
                    ['id' => 2, 'name' => 'Promotion 2', 'created_at' => '2024-01-05', 'updated_at' => '2024-01-06', 'groups' => [201]],
                    ['id' => 3, 'name' => 'Promotion 3', 'created_at' => '2024-02-01', 'updated_at' => '2024-02-02', 'groups' => []],
                ]
            ],
        ];
    }
}
