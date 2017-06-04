<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as IlluminateRequest;

class Request extends IlluminateRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [];
    }
}
