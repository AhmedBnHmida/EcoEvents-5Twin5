<?php

namespace App\Services;

use App\Models\Interaction;
use Illuminate\Http\Request;

class TrackingService
{
    public static function record(Request $request, array $data = []): Interaction
    {
        // data keys: user_id, event_id, type, value, metadata
        $payload = [
            'user_id' => $data['user_id'] ?? ($request->user()?->id ?? null),
            'session_id' => $data['session_id'] ?? $request->session()->getId(),
            'event_id' => $data['event_id'] ?? null,
            'type' => $data['type'] ?? 'view',
            'value' => $data['value'] ?? null,
            'metadata' => $data['metadata'] ?? [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ];

        return Interaction::create($payload);
    }
}
