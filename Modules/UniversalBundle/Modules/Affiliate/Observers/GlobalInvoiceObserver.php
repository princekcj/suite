<?php

namespace Modules\Affiliate\Observers;

use Modules\Affiliate\Enums\YesNo;
use Modules\Affiliate\Enums\PayoutType;
use App\Models\SuperAdmin\GlobalInvoice;
use Modules\Affiliate\Enums\CommissionType;
use Modules\Affiliate\Entities\AffiliateSetting;
use Modules\Affiliate\Entities\Referral;
use Modules\Affiliate\Enums\PayoutTime;

class GlobalInvoiceObserver
{

    /**
     * Handle the GlobalInvoice "updating" event.
     *
     * @param  \App\Models\SuperAdmin\GlobalInvoice  $invoice
     * @return void
     */
    public function created(GlobalInvoice $invoice)
    {
        $isReferral = Referral::where('company_id', $invoice->company_id)->exists();
        $referral = Referral::where('company_id', $invoice->company_id)->first();

        if ($isReferral && $invoice->package->is_free == 0) {
            $affiliate = $referral->affiliate;
            $affiliateSettings = AffiliateSetting::first();

            if ($affiliateSettings->commission_enabled == YesNo::Yes &&
                $affiliateSettings->payout_type == PayoutType::AfterSignUp) {

                $commission = 0;

                if ($affiliateSettings->commission_type == CommissionType::Fixed) {
                    $commission = $affiliateSettings->commission_cap;
                }
                elseif ($affiliateSettings->commission_type == CommissionType::Percent) {
                    $commission = $invoice->total * $affiliateSettings->commission_cap / 100;
                }

                if ($affiliateSettings->payout_time == PayoutTime::OneTime &&
                    GlobalInvoice::where('company_id', $invoice->company_id)->count() == 2) {
                    $affiliate->balance += $commission;
                    $referral->commissions += $commission;
                }

                if ($affiliateSettings->payout_time == PayoutTime::EveryTime) {
                    $affiliate->balance += $commission;
                    $referral->commissions += $commission;
                }

                $affiliate->save();
                $referral->save();
            }
        }
    }

}
