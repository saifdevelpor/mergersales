<?php

namespace App\Http\Controllers;

use App\Models\SubIndustry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function getSubIndustries($industryId)
    {
        return SubIndustry::where('industry_id', $industryId)->get();
    }
}
