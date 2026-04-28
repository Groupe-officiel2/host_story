<?php

namespace App\Http\Controllers\paypalEvent;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
       //dd($subscription);
        $approveLink = collect($subscription['links'])
            ->firstWhere('rel', 'approve')['href'];

        return redirect($approveLink);
    }

    // PayPal redirects here after approval
    public function success(Request $request, PayPalService $paypal)
    {
        $subscriptionId = $request->query('subscription_id');

       $details = $paypal->getSubscription(subscriptionId: $subscriptionId);

        Subscription::updateOrCreate(
            ['paypal_subscription_id' => $subscriptionId],
            [
                'user_id'                => auth()->id(),
                'plan_id'                => Plan::where('paypal_plan_id', $details['plan_id'])->value('id'),
                'status'                 => $details['status'],
                'next_billing_at'        => Carbon::parse($details['billing_info']['next_billing_time'])->setTimezone('UTC')->toDateTimeString(),
            ]
        );

        return redirect('/dashboard')->with('success', 'Abonnement activé !');
    }
    
    // PayPal redirects here if the user cancels
    public function cancel()
    {
        return redirect('/plans')->with('error', 'Abonnement annulé.');
    }
}