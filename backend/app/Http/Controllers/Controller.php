<?php

namespace App\Http\Controllers;

// ✅ 1. استيراد الكونترولر الأساسي ديال لارافيل
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// ✅ 2. الوراثة من الكونترولر الأساسي
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}