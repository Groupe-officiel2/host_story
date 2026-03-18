<?php

namespace App\Http\Controllers\paypalEvent;

use App\Http\Controllers\Controller;
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
}