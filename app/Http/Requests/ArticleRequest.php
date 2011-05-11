<?php

namespace App\Http\Requests;
use App\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends FormRequest
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
        return [
                'title' => 'required|min:4|unique:articles|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'description' => 'required|min:10',
                'content' => 'required|min:30',
                'category_id' => 'required|exists:categories,id',
                'tags' => 'required|exists:tags,id',
                'public' => 'required|boolean',
        ];
    }
    /**
     *
     * @return array
    */

    public function messages(){
        return [
                'title.required' => 'El titulo es requerido',
                'title.min' => 'El titulo debe tener un minimo de 4 caracteres',
                'title.unique' => 'El titulo ya se encuentra registrado',
                'description.required' => 'La descripción es requerida',
                'description.min' => 'La descripción debe tener un minimo de 10 caracteres',
                'title.regex' => 'Para el titulo solo permiten caracteres alfanumericos',
                'category_id.required' => 'La categoria es requerida',
                'category_id.exists' => 'La categoria es requerida',
                'tags.required' => 'Los tags son requeridos',
                'tags.exists' => 'Los tags son requeridos',
        ];
    }
}
