<?php

namespace App\Http\Controllers\paypalEvent;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    // PayPal sends webhook events
    public function webhook(Request $request)
    {
        $event = $request->all();
        $eventType = $event['event_type'] ?? null;

        match ($eventType) {
            'BILLING.SUBSCRIPTION.ACTIVATED' => $this->handleActivated($event),
            'BILLING.SUBSCRIPTION.CANCELLED' => $this->handleCancelled($event),
            'BILLING.SUBSCRIPTION.SUSPENDED' => $this->handleSuspended($event),
            'PAYMENT.CAPTURE.COMPLETED'      => $this->handlePaymentCompleted($event),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handleActivated(array $event)
    {   // subscription activation
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'ACTIVE']);
    }

    private function handleCancelled(array $event)
    {   // subscription cancellation
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'CANCELLED']);
    }

    private function handleSuspended(array $event)
    {   // subscription suspension
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'SUSPENDED']);
    }

    private function handlePaymentCompleted(array $event)
    {
        // payment successful, update next billing date
        $subscriptionId = $event['resource']['billing_agreement_id'] ?? null;

        if ($subscriptionId) {
            Subscription::where('paypal_subscription_id', $subscriptionId)
                ->update(['renewed_at' => now()]);
        }
    }
}
