<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == 'admin') {
            $loans = Loan::with('repayment')->get();
        } else {
            $loans = Loan::whereBelongsTo(Auth::user())->with('repayment')->get();
        }
        return response([   'loans' => LoanResource::collection($loans) ],
                        200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'loan_amount' => 'required|numeric|min:0|not_in:0',
            'loan_term' => 'required|numeric|min:0|not_in:0',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(),
            'Validation Error']);
        }

        $data['user_id'] = Auth::user()->id;

        $loan = Loan::create($data);

        return response([
                                    'response' => 'Loan requested Successfully'],
                        200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        if ($loan->user_id === Auth::user()->id || Auth::user()->type === 'admin') {
            $loan['repayments'] = $loan->repayment;
            return response([
                                    'loan' => new LoanResource($loan)],
                            200);
        } else {
            return response([
                    'error' => "Unauthorized Access, loan not belongs to you"],
             401);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {

        if (Auth::user()->type=='admin') {
            if ($request->has('status')) {
                if ($loan->status == 'APPROVED') {
                    $status = "Loan Status Already APPROVED";
                    $code = 409 ;
                } else {
                    $loan->update($request->all());
                    for ($index = 1 ; $index <= ($loan->loan_term) ; $index++) {
                        $today = Carbon::now();
                        $repaymentInfo['loan_id'] = $loan->id ;
                        $repaymentInfo['repayment_amount'] = $loan->loan_amount/$loan->loan_term ;
                        $repaymentInfo['repayment_date'] = $today->addDays($index*7);
                        $repaymentInfo['status'] = 'PENDING';
                        $repaymentInfo['paid_on'] = null;
                        $repayment = Repayment::create($repaymentInfo);
                    }
                    $status = "Loan Status Changed Successfully";
                    $code = 200 ;
                }
            } else {
                $status = "You cannot update other information than status";
                $code = 401 ;
            }
        } else {

            if ($loan->user_id === Auth::user()->id) {
                if ($request->has('status')) {
                    $status = "You cannot change status of loan, only admin can do it";
                    $code = 401 ;
                } else {
                    $loan->update($request->except('status'));
                    $status = "Loan Information Changed Successfully";
                    $code = 200 ;
                }
            } else {
                $status = "Unauthorized Access";
                $code = 401 ;
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
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();

        return response(['message' => 'Loan deleted']);
    }
}
