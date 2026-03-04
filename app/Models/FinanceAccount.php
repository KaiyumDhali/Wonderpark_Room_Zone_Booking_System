<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceAccount extends Model {

    use HasFactory;

    public function financeGroup() {
        return $this->belongsTo(FinanceGroup::class, 'financegroup_id');
    }

    protected $fillable = ['financegroup_id', 'account_company_code', 'account_name', 'account_mobile', 'account_email', 'account_address', 'account_group_code', 'account_done_by', 'account_updated_by', 'account_status'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

}
