<?php

namespace App\Observers;

use App\Enums\MaritalStatus;
use Illuminate\Support\Carbon;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveQuota;
use App\Events\NewUserSlackEvent;
use App\Models\User;

class EmployeeDetailsObserver
{

    public function saving(EmployeeDetails $detail)
    {
        if (!isRunningInConsoleOrSeeding() && auth()->check() && user()) {
            $detail->last_updated_by = user()->id;
        }
    }

    public function creating(EmployeeDetails $detail)
    {
        if (!isRunningInConsoleOrSeeding() && auth()->check()) {
            $detail->added_by = user()->id;
        }

        $detail->company_id = $detail->user->company_id;

        if (is_null($detail->marital_status)) {
            $detail->marital_status = MaritalStatus::Single;
        }

    }

    public function created(EmployeeDetails $detail)
    {
        if (!isset($detail->joining_date)) {
            return true;
        }

        $leaveTypes = $detail->company->leaveTypes;
        $settings = company();

        $user = $detail->user;

        event(new NewUserSlackEvent($user));


        foreach ($leaveTypes as $value) {
            $leaves = $value->no_of_leaves;


            if (is_null($detail->joining_date)) {
                return true;
            }

            $joiningDate = $detail->joining_date->copy();
            $daysLeft = ($joiningDate->daysInMonth - $joiningDate->day) + 1;

            if ($settings && $settings->leaves_start_from == 'joining_date') {
                $remainingDays = 0;
                $currentYearJoiningDate = Carbon::parse($detail->joining_date->format((now(company()->timezone)->year) . '-m-d'));;

                $differenceMonth = now()->greaterThan($currentYearJoiningDate)
                    ? now()->diffInMonths($currentYearJoiningDate->addYear())
                    : now()->diffInMonths($currentYearJoiningDate);

                $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;

                // Calculate remaining days after full months
                $remainingDays = now()->diffInDays($currentYearJoiningDate->copy()->subMonths($differenceMonth));
                $remainingDays += 2; // adding 2 for becaus same day and next day is not counting as diff


                if ($remainingDays >= 16) {
                    $countOfMonthsAllowed++;
                    $remainingDays = 0;
                }


            }
            else {
                // yearly setting year_start
                $joiningDate = $joiningDate->addMonth()->startOfMonth();

                $yearFrom = $settings && $settings->year_starts_from ? $settings->year_starts_from : 1;
                $startingDate = Carbon::create(now()->year + 1, $yearFrom)->startOfMonth();


                $differenceMonth = ($detail->joining_date->year == now()->year)
                    ? $joiningDate->diffInMonths($startingDate)
                    : now()->diffInMonths($startingDate);


                $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;


                // $detail->joining_date->year == now()->year &&
                if ($daysLeft >= 16) {
                    $countOfMonthsAllowed++;
                }

            }


            if ($value->leavetype == 'yearly') {
                $leaves = (($value->no_of_leaves / 12) * $countOfMonthsAllowed);
            }
            else {
                $leaves = $value->no_of_leaves; // all leaves for monthly leave type
            }

            // employee joined so cron will not count his joining month yeat
            $month = $detail->joining_date;
            $carryForwardStatus = [];
            $carryForwardStatus[$month->format('F Y')] = true;

            EmployeeLeaveQuota::create(
                [
                    'user_id' => $detail->user_id,
                    'leave_type_id' => $value->id,
                    'no_of_leaves' => round($leaves),
                    'leaves_used' => 0,
                    'leaves_remaining' => round($leaves),
                    'carry_forward_status' => json_encode($carryForwardStatus),
                ]
            );
        }

    }

}
