<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $loanInformation = [
            'loan_id' => $this->id,
            'loan_amount' => $this->loan_amount,
            'loan_term' => $this->loan_term,
            'requested_on' => Carbon::parse($this->created_at)->format('d-M-Y'),
            'status' => $this->status,
            'repayments' => RepaymentResource::collection($this->whenLoaded('repayment'))
        ];
        $repayments = 0;
        if ($loanInformation['repayments']!=null && count($loanInformation['repayments'])>0) {
            foreach ($loanInformation['repayments'] as $repayment) {
                if ($repayment['status'] == 'PAID') {
                    $repayments++;
                }
            }
            if ($repayments==$this->loan_term) {
                $loanInformation['repayment_status'] = 'PAID';
            } else {
                $loanInformation['repayment_status'] = 'PENDING';
            }
        }
        if(Auth::user()->type == 'admin') {
            $loanInformation['requested_by'] = $this->user->name;
        }
        return $loanInformation;
    }
}
