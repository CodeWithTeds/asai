<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Turnstile implements ValidationRule
{
    public function __construct(
        private readonly ?string $action = null
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || ! is_string($value) || strlen($value) > 2048) {
            $fail('Please complete the security verification.');
            return;
        }

        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            if (app()->environment('testing')) {
                return;
            }

            $fail('Security verification is currently misconfigured.');
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if (! $response->successful()) {
                $fail('Security verification service is temporarily unavailable. Please try again.');
                return;
            }

            $data = $response->json();

            if (! ($data['success'] ?? false)) {
                $fail('Security verification failed. Please try again.');
                return;
            }

            if ($this->action !== null && isset($data['action']) && $data['action'] !== $this->action) {
                $fail('Security verification action mismatch.');
                return;
            }
        } catch (\Throwable $e) {
            report($e);
            $fail('Security verification failed due to a connection error. Please try again.');
        }
    }
}
