<?php

namespace App\Http\Requests\WebRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Measure;

class StoreProductRequest extends FormRequest
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
        $data['measure'] = Measure::all();
        foreach($data['measure'] as $item){
            $items[] = $item->id;
        }
        $measure = implode(',',$items);
        //dd($measure);
        return [
            'code'          => 'required|max:25|unique:product,code',
            'name'          => 'required|max:100',
            'measure_id'    => 'required|in:'.$measure,
            'price'         => 'required|numeric',
            'warn_stock'    => 'required|numeric',
        ];
    }
}
