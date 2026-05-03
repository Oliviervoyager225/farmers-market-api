<?php

declare(strict_types=1);

namespace App\Domain\Notification\Controllers;

use App\Domain\Notification\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    /** GET /notifications — all alerts for the authenticated user */
    public function index(Request $request): JsonResponse
    {
        $alerts = $this->notificationService->getAll($request->user()->id);

        return response()->json(['data' => $alerts]);
    }

    /** GET /notifications/unread-count */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'data' => ['count' => $this->notificationService->unreadCount($request->user()->id)],
        ]);
    }

    /** PUT /notifications/{key}/read — mark one notification as read */
    public function markAsRead(Request $request, string $key): JsonResponse
    {
        $this->notificationService->markAsRead($request->user()->id, $key);

        return response()->json(['message' => 'Notification marquée comme lue.']);
    }

    /** PUT /notifications/read-all — mark all as read */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllAsRead($request->user()->id);

        return response()->json(['message' => 'Toutes les notifications marquées comme lues.']);
    }
}
