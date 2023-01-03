<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'post_type'             => 'required|integer',
            'title'                 => 'required|string|max:191',
            'status'                => 'required|integer',
            'subtitle'              => 'required|string|max:191',
            'meta_description'      => 'required|string',
            'themes'                => 'required|array|min:1',
            'themes.*'              => 'required|integer',
            'seo_title'             => 'required|string|max:191',
            'slug'                  => 'required|string|max:191',
            'indexed'               => 'required|integer',
            'url_video_youtube'     => 'nullable|string|max:191|url',
            'url_video_vimeo'       => 'nullable|string|max:191|url',
            'url_audio'             => 'nullable|string|max:191|url',
            'video_transcription'   => 'nullable|string',
            'content'               => 'required|string',
            'header1'               => 'nullable|string',
            'header2'               => 'nullable|string',
            'header3'               => 'nullable|string',
            'header4'               => 'nullable|string',
            'tags'                  => 'required|array|min:1',
            'tags.*'                => 'required|integer',
        ];
    }
}
