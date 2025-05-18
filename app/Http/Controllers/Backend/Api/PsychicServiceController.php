<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\PsychicService;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PsychicServiceController extends Controller
{
    /**
     * Get all psychic services
     *
     * @return JsonResponse
     */
    public function getPsychicsServices(): JsonResponse
    {
        try {
            $services = PsychicService::orderBy('created_at', 'desc')->get();

            $services = $services->map(function ($services) {
                $services->logo = Storage::url($services->logo);
                return $services;
            });
            return ResponseHelper::successResponse(
                $services,
                'Psychic services retrieved successfully'
            );
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse(
                'Failed to retrieve psychic services',
                500,
                $e
            );
        }
    }

    /**
     * Get a specific psychic service
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showPsychicsService(int $id): JsonResponse
    {
        try {
            $service = PsychicService::findOrFail($id);
            $service->logo = Storage::url($service->logo);

            return ResponseHelper::successResponse(
                $service,
                'Psychic service retrieved successfully'
            );
        } catch (\Exception $e) {
            return ResponseHelper::notFoundResponse(
                'Psychic service not found'
            );
        }
    }
}