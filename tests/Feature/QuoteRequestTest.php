<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Tests\TestCase;

class QuoteRequestTest extends TestCase
{
    /** @return array<string, mixed> */
    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'company' => 'شركة اختبار',
            'name' => 'عبدالله',
            'phone' => '0549556517',
            'email' => 'buyer@example.com',
            'city' => 'al-ahsa',
            'service' => 'construction-manpower',
            'workers' => 25,
            'duration' => 'medium',
            'message' => 'نحتاج حدادين ونجارين.',
            'consent' => true,
        ], $overrides);
    }

    protected function form(array $overrides = [])
    {
        $component = Volt::test('public.quote-request');

        foreach ($this->validPayload($overrides) as $field => $value) {
            $component->set($field, $value);
        }

        return $component;
    }

    public function test_a_valid_submission_emails_the_lead_inbox_and_redirects(): void
    {
        Mail::fake();

        $this->form()
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('public.thank-you', ['locale' => 'ar']));

        Mail::assertSent(QuoteRequestMail::class, function (QuoteRequestMail $mail) {
            return $mail->hasTo(config('company.lead_inbox'))
                && $mail->lead['workers'] === 25
                && str_contains($mail->lead['city_label'], 'الأحساء');
        });
    }

    public function test_required_fields_are_enforced(): void
    {
        Mail::fake();

        $this->form(['name' => '', 'phone' => '', 'city' => '', 'service' => '', 'duration' => ''])
            ->call('submit')
            ->assertHasErrors(['name', 'phone', 'city', 'service', 'duration']);

        Mail::assertNothingSent();
    }

    /**
     * @dataProvider saudiMobileNumbers
     */
    public function test_saudi_mobile_formats_are_accepted(string $phone): void
    {
        Mail::fake();

        $this->form(['phone' => $phone])->call('submit')->assertHasNoErrors('phone');
    }

    public static function saudiMobileNumbers(): array
    {
        return [
            'local' => ['0549556517'],
            'no leading zero' => ['549556517'],
            'e164' => ['+966549556517'],
            'international prefix' => ['00966549556517'],
        ];
    }

    public function test_non_saudi_and_landline_numbers_are_rejected(): void
    {
        Mail::fake();

        foreach (['+14155550100', '0112345678', 'not-a-number'] as $phone) {
            $this->form(['phone' => $phone])->call('submit')->assertHasErrors('phone');
        }

        Mail::assertNothingSent();
    }

    public function test_consent_must_be_given(): void
    {
        Mail::fake();

        $this->form(['consent' => false])->call('submit')->assertHasErrors('consent');

        Mail::assertNothingSent();
    }

    public function test_unknown_city_or_service_is_rejected(): void
    {
        Mail::fake();

        $this->form(['city' => 'london'])->call('submit')->assertHasErrors('city');
        $this->form(['service' => 'rocket-science'])->call('submit')->assertHasErrors('service');

        Mail::assertNothingSent();
    }

    public function test_honeypot_blocks_bot_submissions(): void
    {
        Mail::fake();

        $this->form(['website' => 'http://spam.example'])
            ->call('submit')
            ->assertHasErrors('website');

        Mail::assertNothingSent();
    }

    /** A mail outage must surface an error, never a silent success. */
    public function test_delivery_failure_is_reported_and_does_not_redirect(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('resend down'));

        $component = $this->form()->call('submit');

        $component->assertNoRedirect();
        $this->assertSame(__('marketing.contact.error'), $component->get('failure'));
    }
}
