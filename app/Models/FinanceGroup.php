<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceGroup extends Model {

    use HasFactory;

    public function financeAccount() {
        return $this->hasMany(FinanceAccount::class, 'financegroup_id');
    }

    protected $guarded = ['id', 'group_code', 'group_name', 'group_parents', 'group_company_code', 'created_at', 'updated_at'];

}
