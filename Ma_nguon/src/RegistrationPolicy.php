<?php

declare(strict_types=1);

final class RegistrationPolicy
{
    /**
     * Quyết định trạng thái đăng ký dựa trên sức chứa, số người đã đăng ký,
     * trạng thái mở của sự kiện và danh sách chờ.
     */
    public function decide(
        int $capacity,
        int $registered,
        bool $isOpen,
        bool $hasWaitingList
    ): string {
        // 1. Kiểm tra tính hợp lệ của dữ liệu đầu vào:
        // Sức chứa <= 0, số đã đăng ký < 0 hoặc số đã đăng ký > sức chứa -> rejected
        if ($capacity <= 0 || $registered < 0 || $registered > $capacity) {
            return 'rejected';
        }

        // 2. Kiểm tra trạng thái đóng/mở của sự kiện:
        // Sự kiện bị đóng (isOpen == false) -> rejected (kể cả còn chỗ)
        if (!$isOpen) {
            return 'rejected';
        }

        // 3. Nếu sự kiện mở và còn chỗ ($registered < $capacity) -> confirmed
        if ($registered < $capacity) {
            return 'confirmed';
        }

        // 4. Nếu sự kiện mở và đã hết chỗ ($registered >= $capacity):
        // - Có danh sách chờ -> waiting
        // - Không có danh sách chờ -> rejected
        if ($hasWaitingList) {
            return 'waiting';
        }

        return 'rejected';
    }
}