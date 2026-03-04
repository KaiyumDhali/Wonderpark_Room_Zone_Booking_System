<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['company_code', 'delivery_challan_no', 'invoice_no', 'voucher_no', 'voucher_date', 'acid', 'type', 'amount', 'balance_type', 'payment_type', 'to_acc_name', 'narration', 'cheque_no', 'cheque_date', 'cheque_type', 'transaction_date', 'transaction_by', 'invoice_type', 'pending_adjustment', 'status', 'done_by', 'updated_by'];
}
