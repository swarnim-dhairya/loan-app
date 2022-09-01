<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'loan_amount', 'loan_term', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function repayment()
    {
        return $this->hasMany(Repayment::class,'loan_id','id');
    }
}
