<?php

namespace Modules\Affiliate\Listeners;

use App\Events\NewCompanyCreatedEvent;
use Modules\Affiliate\Entities\Affiliate;
use Modules\Affiliate\Entities\AffiliateSetting;
use Modules\Affiliate\Entities\Referral;
use Modules\Affiliate\Enums\CommissionType;
use Modules\Affiliate\Enums\PayoutType;
use Modules\Affiliate\Enums\YesNo;

class CompanyCreatedListener
{

    /**
     * Handle the event.
     */
    public function handle(NewCompanyCreatedEvent $event)
    {

        if (session()->has('referralCode')) {
            $affiliate = Affiliate::where('referral_code', session()->get('referralCode'))
                ->active()
                ->first();

            if ($affiliate) {
                $referral = new Referral();
                $referral->affiliate_id = $affiliate->id;
                $referral->company_id = $event->company->id;
                $referral->ip = request()->getClientIp();
                $referral->user_agent = request()->userAgent();
                $referral->save();

                $affiliateSettings = AffiliateSetting::first();

                if ($affiliateSettings->commission_enabled == YesNo::Yes &&
                    $affiliateSettings->payout_type == PayoutType::OnSignUp &&
                    $affiliateSettings->commission_type == CommissionType::Fixed) {
                        // Add commission to affiliate balance
                        $affiliate->balance += $affiliateSettings->commission_cap;
                        $affiliate->save();

                        // Add commission to referral
                        $referral->commissions = $affiliateSettings->commission_cap;
                        $referral->save();
                }
            }

            session()->forget('referralCode');
        }
    }

}
