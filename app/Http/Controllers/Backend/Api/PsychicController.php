<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Psychic;

class PsychicController extends Controller
{
    /**
     * Get top 3 psychics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopPsychics()
    {
        $topPsychics = Psychic::with(['services'])
            ->select('id', 'profile_name', 'profile_photo', 'tagline', 'profile_description')
            ->orderBy('created_at', 'desc')
            ->take(3)->get();

        $topPsychics = $topPsychics->map(function ($psychic) {
            $psychic->profile_photo = Storage::url($psychic->profile_photo);
            return $psychic;
        });

        return ResponseHelper::apiResponse($topPsychics, 'Top psychics retrieved successfully');
    }

    /**
     * Get all psychics with complete details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPsychics()
    {
        $psychics = Psychic::with([
            'appLinks.appLink',
            'paymentLinks.paymentLink',
            'socialLinks.socialMediaLink',
            'services',
            'address.country'
        ])
            ->select([
                'id',
                'full_name',
                'profile_name',
                'profile_photo',
                'tagline',
                'profile_description',
                'website',
                'email',
                'mobile_number',
                'whatsapp_number',
                'created_at'
            ])
            ->get()
            ->map(function ($psychic) {
                return [
                    'id' => $psychic->id,
                    'full_name' => $psychic->full_name,
                    'profile_name' => $psychic->profile_name,
                    'profile_photo' => Storage::url($psychic->profile_photo),
                    'tagline' => $psychic->tagline,
                    'profile_description' => $psychic->profile_description,
                    'website' => $psychic->website,
                    'contact' => [
                        'email' => $psychic->email,
                        'mobile' => $psychic->mobile_number,
                        'whatsapp' => $psychic->whatsapp_number
                    ],
                    'profile_url' => route('psychic.profile', $psychic->id),
                    'address' => $psychic->address ? [
                        'address_line_1' => $psychic->address->address_line_1,
                        'address_line_2' => $psychic->address->address_line_2,
                        'city' => $psychic->address->show_city ? $psychic->address->city : null,
                        'country' => $psychic->address->show_country ? $psychic->address->country->name : null
                    ] : null,
                    'services' => $psychic->services->map(function ($service) {
                        return [
                            'id' => $service->id,
                            'name' => $service->name,
                            'logo' => Storage::url($service->logo),
                        ];
                    }),
                    'app_links' => $psychic->appLinks->map(function ($appLink) {
                        return [
                            'name' => $appLink->appLink->name,
                            'value' => $appLink->value,
                            'logo' => Storage::url($appLink->logo),
                        ];
                    }),
                    'payment_links' => $psychic->paymentLinks->map(function ($paymentLink) {
                        return [
                            'name' => $paymentLink->paymentLink->name,
                            'value' => $paymentLink->value,
                            'qr_code' => Storage::url($paymentLink->logo)
                        ];
                    }),
                    'social_links' => $psychic->socialLinks->map(function ($socialLink) {
                        return [
                            'platform' => $socialLink->socialMediaLink->name,
                            'url' => $socialLink->value,
                            'logo' => Storage::url($socialLink->logo),
                        ];
                    }),
                    'created_at' => $psychic->created_at
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $psychics
        ]);
    }

    /**
     * Get a single psychic by ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $psychic = Psychic::with([
                'appLinks.appLink',
                'paymentLinks.paymentLink',
                'socialLinks.socialMediaLink',
                'services',
                'address.country'
            ])
                ->select([
                    'id',
                    'full_name',
                    'profile_name',
                    'profile_photo',
                    'tagline',
                    'profile_description',
                    'website',
                    'email',
                    'mobile_number',
                    'whatsapp_number',
                    'created_at'
                ])
                ->findOrFail($id);

            $formattedData = [
                'id' => $psychic->id,
                'full_name' => $psychic->full_name,
                'profile_name' => $psychic->profile_name,
                'profile_photo' => Storage::url($psychic->profile_photo),
                'tagline' => $psychic->tagline,
                'profile_description' => $psychic->profile_description,
                'website' => $psychic->website,
                'mobile_number' => $psychic->mobile_number,
                'whatsapp_number' => $psychic->whatsapp_number,
                'profile_url' => route('psychic.profile', $psychic->id),
                'address' => $psychic->address ? [
                    'address_line_1' => $psychic->address->address_line_1,
                    'address_line_2' => $psychic->address->address_line_2,
                    'city' => $psychic->address->city,
                    'show_city' => $psychic->address->show_city,
                    'show_country' => $psychic->address->show_country,
                    'country' => [
                        'id' => $psychic->address->country?->id,
                        'name' => $psychic->address->country?->name,
                        'code' => $psychic->address->country?->code
                    ]
                ] : null,
                'services' => $psychic->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'service' => $service->service,
                        'logo' => $service->logo ? Storage::url($service->logo) : null,
                        'description' => $service->description,
                        'meta_title' => $service->meta_title,
                        'meta_description' => $service->meta_description,
                        'meta_keywords' => $service->meta_keywords,
                        'created_at' => $service->created_at,
                        'updated_at' => $service->updated_at,
                        'pivot' => $service->pivot
                    ];
                }),
                'app_links' => $psychic->appLinks->map(function ($appLink) {
                    return [
                        'id' => $appLink->id,
                        'psychic_id' => $appLink->psychic_id,
                        'app_link_id' => $appLink->app_link_id,
                        'value' => $appLink->value,
                        'created_at' => $appLink->created_at,
                        'updated_at' => $appLink->updated_at,
                        'app_link' => [
                            'id' => $appLink->appLink->id,
                            'app_name' => $appLink->appLink->app_name,
                            'url_prefix' => $appLink->appLink->url_prefix,
                            'logo' => $appLink->appLink->logo ? Storage::url($appLink->appLink->logo) : null,
                            'created_at' => $appLink->appLink->created_at,
                            'updated_at' => $appLink->appLink->updated_at
                        ]
                    ];
                }),
                'payment_links' => $psychic->paymentLinks->map(function ($paymentLink) {
                    return [
                        'id' => $paymentLink->id,
                        'psychic_id' => $paymentLink->psychic_id,
                        'payment_link_id' => $paymentLink->payment_link_id,
                        'value' => $paymentLink->value,
                        'qr_code' => $paymentLink->qr_code,
                        'created_at' => $paymentLink->created_at,
                        'updated_at' => $paymentLink->updated_at,
                        'payment_link' => [
                            'id' => $paymentLink->paymentLink->id,
                            'payment_provider' => $paymentLink->paymentLink->payment_provider,
                            'url_prefix' => $paymentLink->paymentLink->url_prefix,
                            'logo' => $paymentLink->paymentLink->logo ? Storage::url($paymentLink->paymentLink->logo) : null,
                            'created_at' => $paymentLink->paymentLink->created_at,
                            'updated_at' => $paymentLink->paymentLink->updated_at
                        ]
                    ];
                }),
                'social_links' => $psychic->socialLinks->map(function ($socialLink) {
                    return [
                        'id' => $socialLink->id,
                        'psychic_id' => $socialLink->psychic_id,
                        'social_media_link_id' => $socialLink->social_media_link_id,
                        'value' => $socialLink->value,
                        'created_at' => $socialLink->created_at,
                        'updated_at' => $socialLink->updated_at,
                        'social_media_link' => [
                            'id' => $socialLink->socialMediaLink->id,
                            'social_site' => $socialLink->socialMediaLink->social_site,
                            'url_prefix' => $socialLink->socialMediaLink->url_prefix,
                            'logo' => $socialLink->socialMediaLink->logo ? Storage::url($socialLink->socialMediaLink->logo) : null,
                            'created_at' => $socialLink->socialMediaLink->created_at,
                            'updated_at' => $socialLink->socialMediaLink->updated_at
                        ]
                    ];
                }),
                'created_at' => $psychic->created_at,
                'updated_at' => $psychic->updated_at
            ];

            return ResponseHelper::apiResponse($formattedData, 'Psychic retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse(
                'Failed to retrieve psychic information',
                404,
                $e
            );
        }
    }
}
