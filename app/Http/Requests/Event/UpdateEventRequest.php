<?php

namespace App\Http\Requests\Event;

use App\Enums\EventStatus;
use App\Enums\EventType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\Event $event */
        $event = $this->route('event');

        return $this->user()?->can('update', $event) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string', 'max:2000'],
            'type'         => ['required', new Enum(EventType::class)],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
            'status'       => ['sometimes', new Enum(EventStatus::class)],
            'starts_at'    => ['nullable', 'date'],
            'expires_at'   => ['nullable', 'date', 'after:starts_at'],
        ];
    }
}
