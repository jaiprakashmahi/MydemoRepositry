<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLocationLogBatchRequest;
use App\Http\Requests\Api\StoreLocationLogRequest;
use App\Models\LocationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LocationLogController extends Controller
{
    /**
     * Save a single GPS ping (normal case: app is online and pings every
     * 1-5 minutes while on duty).
     */
    public function store(StoreLocationLogRequest $request): JsonResponse
    {
        $log = LocationLog::updateOrCreate(
            [
                'employee_id' => $request->integer('employee_id'),
                'recorded_at' => $request->date('recorded_at'),
            ],
            [
                'latitude' => (float) $request->input('latitude'),
                'longitude' => (float) $request->input('longitude'),
                'accuracy_meters' => $request->input('accuracy_meters'),
                'battery_level' => $request->input('battery_level'),
                'place_name' => $request->input('place_name'),
            ]
        );

        return response()->json([
            'status' => 'ok',
            'data' => $log,
        ], 201);
    }

    /**
     * Save a batch of queued pings (app was offline, catches up once it
     * regains connectivity). Wrapped in a transaction so a partial batch
     * failure doesn't leave gaps in the day's route.
     */
    public function storeBatch(StoreLocationLogBatchRequest $request): JsonResponse
    {
        $employeeId = $request->integer('employee_id');
        $saved = 0;

        DB::transaction(function () use ($request, $employeeId, &$saved) {
            foreach ($request->input('pings') as $ping) {
                LocationLog::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'recorded_at' => $ping['recorded_at'],
                    ],
                    [
                        'latitude' => $ping['latitude'],
                        'longitude' => $ping['longitude'],
                        'accuracy_meters' => $ping['accuracy_meters'] ?? null,
                        'battery_level' => $ping['battery_level'] ?? null,
                        'place_name' => $ping['place_name'] ?? null,
                    ]
                );
                $saved++;
            }
        });

        return response()->json([
            'status' => 'ok',
            'saved' => $saved,
        ], 201);
    }
}
