<?php

namespace App\Http\Requests\Post;

use App\Models\Post\Post;
use App\Models\Workspace\Workspaces;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'live_at' => 'required|date',
            'user_id' => 'required',
            'workspace_id' => 'required',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:500'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The post title is required.',
            'title.string' => 'The post title must be a string.',
            'title.max' => 'The post title may not be longer than 255 characters.',
            'description.required' => 'The post description is required.',
            'description.string' => 'The post description must be a string.',
            'live_at.required' => 'The date to go live is required.',
            'live_at.date' => 'The date to go live must be a valid date.',
            'workspace_id.required' => 'The workspace is required.',
        ];
    }
}

// 'workspace_id' => 'required',
            // 'workspace_id' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         $workspace = Workspaces::find($value);
            //         if (!$workspace || $workspace->user_id !== $this->user()->id) {
            //             $fail("The selected workspace is invalid or doesn't belong to the authenticated user.");
            //         }
            //     },
            // ],