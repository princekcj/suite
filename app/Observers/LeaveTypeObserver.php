<?php

namespace App\Observers;

use App\Models\LeaveType;
use Illuminate\Support\Carbon;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveQuota;

class LeaveTypeObserver
{

    public function creating(LeaveType $leaveType)
    {
        if (company()) {
            $leaveType->company_id = company()->id;
        }
    }

    public function created(LeaveType $leaveType)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $employees = EmployeeDetails::select('id', 'user_id', 'joining_date')->get();
            $settings = company();

            foreach ($employees as $key => $employee) {
               
                $leaves = $leaveType->no_of_leaves;

                    $joiningDate = $employee->joining_date->copy();
                    $daysLeft = ($joiningDate->daysInMonth - $joiningDate->day) + 1;                     

                    if ($settings->leaves_start_from == 'joining_date') {
                        $remainingDays = 0; 
                        $currentYearJoiningDate = Carbon::parse($employee->joining_date->format((now(company()->timezone)->year) . '-m-d'));
                       

                                $differenceMonth = now()->greaterThan($currentYearJoiningDate)
                                ? now()->diffInMonths($currentYearJoiningDate->addYear())
                                : now()->diffInMonths($currentYearJoiningDate);

                                $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;

                                // Calculate remaining days after full months
                                $remainingDays = now()->diffInDays($currentYearJoiningDate->copy()->subMonths($differenceMonth));
                                $remainingDays +=  2; // adding 2 for becaus same day and next day is not counting as diff
                              

                                if ($remainingDays >= 16) {
                                    $countOfMonthsAllowed++;
                                    $remainingDays = 0; 
                                }

                            
                        
                    }else{
                        // yearly setting year_start
                        $joiningDate = $joiningDate->addMonth()->startOfMonth();
                        $startingDate = Carbon::create(now()->year + 1, $settings->year_starts_from)->startOfMonth();

                        $differenceMonth = ($employee->joining_date->year == now()->year )
                                ? $joiningDate->diffInMonths($startingDate) 
                                : now()->diffInMonths($startingDate) ;

                        
                        $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;
                                                
                        // $employee->joining_date->year == now()->year && 
                        if ($daysLeft >= 16) {
                            $countOfMonthsAllowed++; 
                        }

                    
                        
                    }


                    if ($leaveType->leavetype == 'yearly'){
                        $leaves = (($leaveType->no_of_leaves / 12) * $countOfMonthsAllowed);
                    }else{
                        $leaves = $leaveType->no_of_leaves; // all leaves for monthly leave type
                    }
                    
                    

                EmployeeLeaveQuota::create(
                    [
                        'user_id' => $employee->user_id,
                        'leave_type_id' => $leaveType->id,
                        'no_of_leaves' => round($leaves),
                        'leaves_used' => 0,
                        'leaves_remaining' => round($leaves),
                    ]
                );
            }
        }
    }

    public function updated(LeaveType $leaveType)
    {
      
        if (
                request()->has('restore') && request()->restore == 'restore' ||
                 ((session()->has('old_leaves') && session('old_leaves') == $leaveType->no_of_leaves) && (session()->has('old_leavetype') && session('old_leavetype') == $leaveType->leavetype))
            ) {

                if (session()->has('old_leaves')) {
                    session()->forget('old_leaves');
                }
            return true;
        }
        
        if (!isRunningInConsoleOrSeeding()) {
           
            $employeeLeaveQuotaUserIds = EmployeeLeaveQuota::where('leave_type_id', $leaveType->id)->where('leave_type_impact', 1)
                    ->pluck('user_id')
                    ->toArray();
                   
            $employees = EmployeeDetails::select('id', 'user_id', 'joining_date')->whereNotIn('user_id', $employeeLeaveQuotaUserIds)->get();
    
            $settings = company();
    
            foreach ($employees as $employee) {
                $leaves = $leaveType->no_of_leaves;
               

                    $joiningDate = $employee->joining_date->copy();
                    $daysLeft = ($joiningDate->daysInMonth - $joiningDate->day) + 1; 

                    if ($settings->leaves_start_from == 'joining_date') {
                        $currentYearJoiningDate = Carbon::parse($employee->joining_date->format((now(company()->timezone)->year) . '-m-d'));
                       

                                $differenceMonth = now()->greaterThan($currentYearJoiningDate)
                                ? now()->diffInMonths($currentYearJoiningDate->addYear())
                                : now()->diffInMonths($currentYearJoiningDate);

                                $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;

                                // Calculate remaining days after full months
                                $remainingDays = now()->diffInDays($currentYearJoiningDate->copy()->subMonths($differenceMonth));
                                $remainingDays +=  2; // adding 2 for becaus same day and next day is not counting as diff
                               

                                if ($remainingDays >= 16) {
                                    $countOfMonthsAllowed++;
                                    $remainingDays = 0; 
                                }

                        
                        
                    }else{
                        $joiningDate = $joiningDate->addMonth()->startOfMonth();
                       
                        $startingDate = Carbon::create(now()->year + 1, $settings->year_starts_from)->startOfMonth();
                       

                        $differenceMonth = ($employee->joining_date->year == now()->year )
                                ? $joiningDate->diffInMonths($startingDate) 
                                : now()->diffInMonths($startingDate) ;
            
                        $countOfMonthsAllowed = $differenceMonth > 12 ? $differenceMonth - 12 : $differenceMonth;
                       
                        // $employee->joining_date->year == now()->year && 
                        if ($daysLeft >= 16) {
                            $countOfMonthsAllowed++; 
                        }
                     
                    }
                        
                    if ($leaveType->leavetype == 'yearly'){

                        $leaves = round((($leaveType->no_of_leaves / 12) * $countOfMonthsAllowed));

                    }else{
                        $leaves = round($leaveType->no_of_leaves);
                    }


                $employeeLeaveQuota = EmployeeLeaveQuota::where('user_id', $employee->user_id)
                    ->where('leave_type_id', $leaveType->id)
                    ->first();

               
                
                if ($employeeLeaveQuota) {
                    $leavesUsed = $employeeLeaveQuota->leaves_used;

                    $noOfLeaves = ($leavesUsed > $leaves) ? $leavesUsed : $leaves;
                    $leaveRemain = ($leaves >= $leavesUsed) ? $leaves - $leavesUsed : 0;

                    $employeeLeaveQuota->update([
                        'no_of_leaves' => $noOfLeaves,
                        'leaves_remaining' => $leaveRemain,
                    ]);

                } else {
                    EmployeeLeaveQuota::create([
                        'user_id' => $employee->user_id,
                        'leave_type_id' => $leaveType->id,
                        'no_of_leaves' => $leaves,
                        'leaves_used' => 0,
                        'leaves_remaining' => $leaves,
                    ]);
                }
            }

            $keysToForget = ['old_leaves', 'old_leavetype'];

            foreach ($keysToForget as $key) {
                if (session()->has($key)) {
                    session()->forget($key);
                }
            }
        }
    }
}
