<?php
declare(strict_types=1);

namespace Kodano\Interview\Api;

interface PromotionServiceInterface
{
    /**
     * @return string
     */
    public function get(): string;

    /**
     * @param int $promotionId
     * @return string
     */
    public function remove(int $promotionId): string;

    /**
     * @param string $promotionName
     * @return mixed
     */
    public function add(string $promotionName): string;
}
