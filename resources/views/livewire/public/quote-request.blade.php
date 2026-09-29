<?php

use App\Mail\QuoteRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $company = '';

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $city = '';

    public string $service = '';

    public int $workers = 5;

    public string $duration = '';

    public string $message = '';

    public bool $consent = false;

    /** Honeypot. Real users never see it; bots fill everything they find. */
    public string $website = '';

    public ?string $failure = null;

    public function rules(): array
    {
        return [
            'company' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:100'],
            // Saudi mobile numbers, with or without +966 / 00966 / leading 0.
            'phone' => ['required', 'string', 'regex:/^(\+?966|00966|0)?5\d{8}$/'],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'city' => ['required', Rule::in(array_keys(config('company.cities')))],
            'service' => ['required', Rule::in(array_keys(config('company.services')))],
            'workers' => ['required', 'integer', 'min:1', 'max:5000'],
            'duration' => ['required', Rule::in(['short', 'medium', 'long', 'ongoing'])],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'company' => __('marketing.contact.form.company'),
            'name' => __('marketing.contact.form.name'),
            'phone' => __('marketing.contact.form.phone'),
            'email' => __('marketing.contact.form.email'),
            'city' => __('marketing.contact.form.city'),
            'service' => __('marketing.contact.form.service'),
            'workers' => __('marketing.contact.form.workers'),
            'duration' => __('marketing.contact.form.duration'),
            'message' => __('marketing.contact.form.message'),
            'consent' => __('marketing.contact.form.consent'),
        ];
    }

    public function submit()
    {
        $this->failure = null;

        $data = $this->validate();

        $key = 'quote:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $this->failure = __('marketing.contact.error');

            return null;
        }

        RateLimiter::increment($key, decaySeconds: 3600);

        $locale = app()->getLocale();

        $lead = [
            'company' => $data['company'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'city_label' => config("company.cities.{$data['city']}.ar").' / '.config("company.cities.{$data['city']}.en"),
            'service_label' => __("marketing.services.{$data['service']}.name", [], 'ar'),
            'workers' => $data['workers'],
            'duration_label' => __("marketing.contact.form.duration_options.{$data['duration']}", [], 'ar'),
            'message' => $data['message'],
            'locale' => $locale,
            'source' => url()->previous(),
            'received_at' => now()->format('Y-m-d H:i').' UTC',
        ];

        try {
            Mail::to(config('company.lead_inbox'))->send(new QuoteRequestMail($lead));
        } catch (\Throwable $e) {
            // Never lose a lead to a mail outage — log it so it can be recovered.
            Log::error('Quote request delivery failed', ['error' => $e->getMessage(), 'lead' => $lead]);

            $this->failure = __('marketing.contact.error');

            return null;
        }

        return $this->redirect(route('public.thank-you', ['locale' => $locale]), navigate: false);
    }
}; ?>

@php
    $locale = app()->getLocale();
    $f = 'marketing.contact.form';
    $field = 'w-full rounded-xl border border-hairline bg-surface px-4 py-2.5 text-sm text-ink placeholder:text-ink-faint focus:border-accent focus:ring-1 focus:ring-accent';
@endphp

<form wire:submit="submit" class="glass-panel p-6 sm:p-8" novalidate>
    @if ($failure)
        <p role="alert" class="mb-6 rounded-xl border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">{{ $failure }}</p>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="q-company" class="mb-1.5 block text-sm font-medium">{{ __("$f.company") }}</label>
            <input id="q-company" type="text" wire:model="company" autocomplete="organization" class="{{ $field }}">
            @error('company') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-name" class="mb-1.5 block text-sm font-medium">{{ __("$f.name") }} <span class="text-danger">*</span></label>
            <input id="q-name" type="text" wire:model="name" autocomplete="name" required class="{{ $field }}">
            @error('name') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-phone" class="mb-1.5 block text-sm font-medium">{{ __("$f.phone") }} <span class="text-danger">*</span></label>
            <input id="q-phone" type="tel" inputmode="tel" dir="ltr" wire:model="phone" autocomplete="tel" placeholder="05XXXXXXXX" required class="{{ $field }}">
            @error('phone') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-email" class="mb-1.5 block text-sm font-medium">{{ __("$f.email") }}</label>
            <input id="q-email" type="email" dir="ltr" wire:model="email" autocomplete="email" class="{{ $field }}">
            @error('email') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-city" class="mb-1.5 block text-sm font-medium">{{ __("$f.city") }} <span class="text-danger">*</span></label>
            <select id="q-city" wire:model="city" required class="{{ $field }}">
                <option value="">{{ __("$f.city_placeholder") }}</option>
                @foreach (config('company.cities') as $slug => $c)
                    <option value="{{ $slug }}">{{ $c[$locale] ?? $c['en'] }}</option>
                @endforeach
            </select>
            @error('city') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-service" class="mb-1.5 block text-sm font-medium">{{ __("$f.service") }} <span class="text-danger">*</span></label>
            <select id="q-service" wire:model="service" required class="{{ $field }}">
                <option value="">{{ __("$f.service_placeholder") }}</option>
                @foreach (array_keys(config('company.services')) as $slug)
                    <option value="{{ $slug }}">{{ __("marketing.services.$slug.name") }}</option>
                @endforeach
            </select>
            @error('service') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-workers" class="mb-1.5 block text-sm font-medium">{{ __("$f.workers") }} <span class="text-danger">*</span></label>
            <input id="q-workers" type="number" min="1" max="5000" dir="ltr" wire:model="workers" required class="{{ $field }}">
            @error('workers') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="q-duration" class="mb-1.5 block text-sm font-medium">{{ __("$f.duration") }} <span class="text-danger">*</span></label>
            <select id="q-duration" wire:model="duration" required class="{{ $field }}">
                <option value="">{{ __("$f.duration_placeholder") }}</option>
                @foreach (__("$f.duration_options") as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('duration') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="q-message" class="mb-1.5 block text-sm font-medium">{{ __("$f.message") }}</label>
            <textarea id="q-message" rows="4" wire:model="message" placeholder="{{ __("$f.message_placeholder") }}" class="{{ $field }}"></textarea>
            @error('message') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Honeypot: hidden from users and assistive tech, irresistible to bots. --}}
    <div class="hidden" aria-hidden="true">
        <label for="q-website">Website</label>
        <input id="q-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
    </div>

    <label class="mt-6 flex items-start gap-2.5 text-sm text-ink-muted">
        <input type="checkbox" wire:model="consent" required class="mt-0.5 rounded border-hairline bg-surface text-accent focus:ring-accent">
        <span>{{ __("$f.consent") }}</span>
    </label>
    @error('consent') <p class="mt-1.5 text-xs text-danger">{{ $message }}</p> @enderror

    <button type="submit" wire:loading.attr="disabled" class="mt-6 w-full rounded-xl bg-accent px-6 py-3 font-semibold text-accent-contrast shadow-glow-accent disabled:opacity-60 sm:w-auto">
        <span wire:loading.remove wire:target="submit">{{ __("$f.submit") }}</span>
        <span wire:loading wire:target="submit">{{ __("$f.sending") }}</span>
    </button>

    <p class="mt-4 text-xs text-ink-faint">{{ __('marketing.common.response_time') }}</p>
</form>
