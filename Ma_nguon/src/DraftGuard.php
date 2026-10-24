<?php
declare(strict_types=1);

function canComplete(array $session, int $eventId, int $now, int $ttl): bool
{
    if ($ttl <= 0) {
        return false;
    }

    if (!isset($session['draft']) || !is_array($session['draft'])) {
        return false;
    }

    $draft = $session['draft'];

    if (!isset($draft['data'], $draft['created_at']) || !is_array($draft['data'])) {
        return false;
    }

    $createdAt = (int)$draft['created_at'];

    if ($createdAt > $now) {
        return false;
    }

    $age = $now - $createdAt;
    if ($age >= $ttl) {
        return false;
    }

    $draftEventId = (int)($draft['data']['event_id'] ?? 0);
    if ($draftEventId <= 0 || $draftEventId !== $eventId) {
        return false;
    }

    return true;
}