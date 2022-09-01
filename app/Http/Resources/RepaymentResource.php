<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RepaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $repaymentInfo = [
            'repayment_id'=>$this->id,
            'repayment_amount' => $this->repayment_amount,
            'repayment_due' => Carbon::parse($this->repayment_date)->format('d-M-Y'),
            'status' => $this->status,
        ] ;
        if($this->status=='PAID') {
            $repaymentInfo['paid_on'] = Carbon::parse($this->paid_on)->format('d-M-Y');
        }
        
        return $repaymentInfo ;
    }
}
