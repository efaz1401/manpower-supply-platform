<x-mail::message>
# طلب عرض سعر جديد

<x-mail::table>
| البند | القيمة |
| :--- | :--- |
| المنشأة | {{ $lead['company'] ?: '—' }} |
| المسؤول | {{ $lead['name'] }} |
| الجوال | {{ $lead['phone'] }} |
| البريد | {{ $lead['email'] ?: '—' }} |
| المدينة | {{ $lead['city_label'] }} |
| التخصص | {{ $lead['service_label'] }} |
| عدد العمال | {{ $lead['workers'] }} |
| المدة | {{ $lead['duration_label'] }} |
</x-mail::table>

@if ($lead['message'])
**تفاصيل إضافية**

{{ $lead['message'] }}
@endif

@if ($lead['phone'])
<x-mail::button :url="'tel:'.$lead['phone']">
اتصل بالعميل
</x-mail::button>
@endif

<small>
اللغة المستخدمة في الموقع: {{ $lead['locale'] }} · الصفحة: {{ $lead['source'] }}<br>
وصل في {{ $lead['received_at'] }}
</small>
</x-mail::message>
