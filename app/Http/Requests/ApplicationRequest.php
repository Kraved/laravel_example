<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Application;
use App\Models\Attachment;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
        $youtubePattern = '/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/';

        return [
            'name' => ['required', 'min: 5', 'max:255'],
            'age' => ['required', 'integer'],
            'location' => ['required', 'min: 2', 'max:255'],
            'contacts' => ['required', 'min: 2', 'max:1024'],
            'video_youtube' => ['required', 'max:512', 'regex:' . $youtubePattern],
            'video_description' => ['required', 'integer', function ($attribute, $value, $fail) {
                $attachment = Attachment::find($value);

                if ($attachment) {
                    if ($attachment->session_id != $this->session->getId()) {
                        $fail('Загрузите ' . $attribute . ' ещё раз');
                    }
                }
            }],
            'confirmation' => ['required'],
            'rules_confirmation' => ['required'],
            'nomination' => ['required', 'integer'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return Application::labels();
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'video_youtube.regex' => 'Укажите ссылку на YouTube',
            'confirmation.required' => 'Необходимо дать согласие на обработку персональных данных',
            'rules_confirmation.required' => 'Необходимо подтвердить согласие с условиями конкурса'
        ];
    }
}
