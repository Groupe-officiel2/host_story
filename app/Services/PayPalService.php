<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;

class PayPalService
{
    public function getAccessToken()
    {
        $response = Http::asForm()
            ->withBasicAuth(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
            ->post(config('services.paypal.base_url') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return $response['access_token'];
    }

    public function createSubscription($planId)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post(config('services.paypal.base_url') . '/v1/billing/subscriptions', [
                "plan_id" => $planId,
                "application_context" => [
                    "return_url" => route('subscription.success'),
                    "cancel_url" => route('subscription.cancel')
                ]
            ]);

        return $response->json();
    }

    public function getSubscription($subscriptionId)
{
    $token = $this->getAccessToken();

    $response = Http::withToken($token)
        ->get(config('services.paypal.base_url') . '/v1/billing/subscriptions/' . $subscriptionId);

    return $response->json();
}
}