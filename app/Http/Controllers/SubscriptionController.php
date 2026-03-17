<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
public function index()
{
    $plans = Plan::all();
    return view('plans.index', compact('plans'));
}
    public function subscribe(Plan $plan, PayPalService $paypal)
    {
        $subscription = $paypal->createSubscription($plan->paypal_plan_id);
        dd($subscription);
        $approveLink = collect($subscription['links'])
            ->firstWhere('rel', 'approve')['href'];

        return redirect($approveLink);
    }

    // PayPal redirige ici après approbation
    public function success(Request $request, PayPalService $paypal)
    {
        $subscriptionId = $request->query('subscription_id');

       $details = $paypal->getSubscription(subscriptionId: $subscriptionId);

        Subscription::updateOrCreate(
            ['paypal_subscription_id' => $subscriptionId],
            [
                'user_id'                => (int) auth()->id(),
                'plan_id'                => (int) Plan::where('paypal_plan_id', $details['plan_id'])->value('id'),
                'status'                 => $details['status'],
                'next_billing_at'        => $details['billing_info']['next_billing_time'] ?? null,
            ]
        );

        return redirect('/dashboard')->with('success', 'Abonnement activé !');
    }

    // PayPal redirige ici si l'utilisateur annule
    public function cancel()
    {
        return redirect('/plans')->with('error', 'Abonnement annulé.');
    }

    // PayPal envoie les événements webhook ici
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
    {
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'ACTIVE']);
    }

    private function handleCancelled(array $event)
    {
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'CANCELLED']);
    }

    private function handleSuspended(array $event)
    {
        $subscriptionId = $event['resource']['id'];
        Subscription::where('paypal_subscription_id', $subscriptionId)
            ->update(['status' => 'SUSPENDED']);
    }

    private function handlePaymentCompleted(array $event)
    {
        // Log ou mise à jour de la date de renouvellement si besoin
    }
}