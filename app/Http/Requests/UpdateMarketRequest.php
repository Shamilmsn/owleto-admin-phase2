<?php
/**
 * File name: UpdateMarketRequest.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: Pixbit Solutions - https://pixbitsolutions.com
 * Copyright (c) 2020
 *
 */

namespace App\Http\Requests;

use App\Models\Market;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();

        $input['drivers'] = isset($input['drivers']) ? $input['drivers'] : [];

        if (auth()->user()->hasRole('admin')) {
            $input['users'] = isset($input['users']) ? $input['users'] : [];
            $input['fields'] = isset($input['fields']) ? $input['fields'] : [];
            $this->replace($input);
            return Market::$adminRules;

        } else {
            unset($input['users']);
            unset($input['fields']);
            $this->replace($input);
            return Market::$managerRules;
        }


    }
}
