<?php

namespace App\Http\Controllers;

use App\Http\Resources\RepaymentResource;
use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function show(Repayment $repayment)
    {
        return response([
                        'repayment' => new RepaymentResource($repayment)],
                200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repayment $repayment)
    {
        if (Auth::user()->type=='admin') {
            $status = "As a Admin, You cannot update status of repayment";
            $code = 401 ;
        } else {
            if ($repayment->status != "PAID") {
                $repaymentInformation = $request->all();
                $repaymentInformation['paid_on'] = Carbon::now();
                $repayment->update($repaymentInformation);
                $status = "Repayment Saved Successfully";
                $code = 200 ;
            } else {
                $status = "This Repayment is already PAID";
                $code = 409 ;
            }
        }
        return response(
                [
                    'response' => $status],
                $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Repayment $repayment)
    {
        //
    }
}
