<x-mail::message>
{{-- Lời chào --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Rất tiếc!')
@else
# @lang('Chào Duy thân mến! 👋')
@endif
@endif

{{-- Các dòng giới thiệu --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Nút hành động --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Các dòng kết thúc --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Lời chào tạm biệt --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Trân trọng,')<br>
{{ config('app.name') }}
@endif

{{-- Phần phụ lục (Link dự phòng khi nút bị lỗi) --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Nếu Duy không bấm được vào nút \":actionText\", hãy copy và dán đường dẫn dưới đây\n".
    'vào trình duyệt web của cậu nhé:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>