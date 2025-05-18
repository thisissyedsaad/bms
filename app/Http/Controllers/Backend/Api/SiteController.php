<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class SiteController extends Controller
{
    public function index(): JsonResponse
    {
        $site = Site::first();

        if (!$site) {
            return ResponseHelper::notFoundResponse('Site settings not found');
        }

        $data = [
            'name' => $site->name,
            'domain' => $site->domain,
            'logo' => asset('storage/' . $site->logo),
            'maintenance_mode' => (bool) $site->maintenance_mode,
            'maintenance_message' => $site->maintenance_message,
            'registration_disabled' => (bool) $site->registration_disabled,
            'registration_message' => $site->registration_message
        ];

        return ResponseHelper::successResponse($data, 'Site settings retrieved successfully');
    }
}
