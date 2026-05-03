<?php

declare(strict_types=1);

namespace App\Domain\Notification\Services;

use App\Models\Debt;
use App\Models\Farmer;
use App\Models\NotificationRead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class NotificationService
{
    // Farmers using >= this fraction of their credit limit trigger a near-limit alert
    private const NEAR_LIMIT_THRESHOLD = 0.80;

    // Debts open longer than this many days trigger an overdue alert
    private const OVERDUE_DAYS = 30;

    /**
     * Returns all active alerts (computed from live data).
     * Each alert is marked as read/unread based on user's read history.
     */
    public function getAll(int $userId): array
    {
        $readKeys = NotificationRead::where('user_id', $userId)
            ->pluck('read_at', 'notification_key');

        $alerts = collect();

        // ── 1. Near / over credit limit ──────────────────────────────────────
        $farmers = Farmer::with([
            'debts' => fn ($q) => $q->whereIn('status', ['open', 'partial']),
        ])->get();

        foreach ($farmers as $farmer) {
            if ($farmer->credit_limit_fcfa <= 0) {
                continue;
            }

            $debt = (float) $farmer->debts->sum('remaining_amount_fcfa');
            $pct  = $debt / $farmer->credit_limit_fcfa;

            if ($pct >= self::NEAR_LIMIT_THRESHOLD) {
                $key  = "near_limit:{$farmer->id}";
                $over = $pct >= 1.0;

                $alerts->push([
                    'key'         => $key,
                    'type'        => $over ? 'over_limit' : 'near_limit',
                    'farmer_id'   => $farmer->id,
                    'farmer_name' => $farmer->full_name,
                    'title'       => $over
                        ? 'Limite de crédit dépassée'
                        : 'Limite de crédit proche',
                    'body'        => sprintf(
                        '%s : %s / %s FCFA utilisés (%d%%)',
                        $farmer->full_name,
                        number_format($debt, 0, ',', ' '),
                        number_format($farmer->credit_limit_fcfa, 0, ',', ' '),
                        (int) round($pct * 100),
                    ),
                    'read_at'     => isset($readKeys[$key])
                        ? Carbon::parse($readKeys[$key])->toISOString()
                        : null,
                    'created_at'  => now()->toISOString(),
                ]);
            }
        }

        // ── 2. Overdue debts (open > OVERDUE_DAYS days) ──────────────────────
        $overdueDebts = Debt::with('farmer')
            ->whereIn('status', ['open', 'partial'])
            ->where('created_at', '<', now()->subDays(self::OVERDUE_DAYS))
            ->get();

        foreach ($overdueDebts as $debt) {
            $key  = "overdue_debt:{$debt->id}";
            $days = (int) $debt->created_at->diffInDays(now());

            $alerts->push([
                'key'         => $key,
                'type'        => 'overdue_debt',
                'farmer_id'   => $debt->farmer_id,
                'farmer_name' => $debt->farmer->full_name,
                'title'       => 'Dette en retard',
                'body'        => sprintf(
                    '%s : dette de %s FCFA en retard depuis %d jours.',
                    $debt->farmer->full_name,
                    number_format($debt->remaining_amount_fcfa, 0, ',', ' '),
                    $days,
                ),
                'read_at'     => isset($readKeys[$key])
                    ? Carbon::parse($readKeys[$key])->toISOString()
                    : null,
                'created_at'  => $debt->created_at->toISOString(),
            ]);
        }

        // Unread first, then most recent
        return $alerts
            ->sortBy([
                fn ($a) => $a['read_at'] === null ? 0 : 1,
                fn ($a) => $a['created_at'],
            ])
            ->values()
            ->all();
    }

    public function unreadCount(int $userId): int
    {
        return collect($this->getAll($userId))
            ->filter(fn ($a) => $a['read_at'] === null)
            ->count();
    }

    public function markAsRead(int $userId, string $key): void
    {
        NotificationRead::updateOrCreate(
            ['user_id' => $userId, 'notification_key' => $key],
            ['read_at' => now()],
        );
    }

    public function markAllAsRead(int $userId): void
    {
        $alerts = $this->getAll($userId);

        foreach ($alerts as $alert) {
            if ($alert['read_at'] === null) {
                $this->markAsRead($userId, $alert['key']);
            }
        }
    }
}
