<?php
declare(strict_types=1);

namespace Kodano\Interview\Api;

interface PromotionGroupServiceInterface
{
    /**
     * @return string
     */
    public function get(): string;

    /**
     * @param string $groupName
     * @return string
     */
    public function add(string $groupName): string;

    /**
     * @param int $groupId
     * @return string
     */
    public function remove(int $groupId): string;

    /**
     * @param int|null $groupId
     * @param int|null $promotionId
     *
     * @return string
     */
    public function addPromotionToGroup(int $groupId = null, int $promotionId = null): string;
}
