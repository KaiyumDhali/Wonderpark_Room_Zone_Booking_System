<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_details',
        'proprietor_name',
        'company_address',
        'factory_address',
        'company_mobile',
        'company_phone',
        'company_email',
        'website_url',
        'fb_url',
        'twitter_url',
        'linkdin_url',
        'instagram_url',
        'trade_license',
        'tin_no',
        'bin_no',
        'vat_no',
        'vat_rate',
        'currency',
        'water_mark',
        'company_logo_one',
        'company_logo_two',
        'company_footer_logo',
        'discount_limit',
        'multiple_spot_discount_limit',
        'status',
    ];
}
