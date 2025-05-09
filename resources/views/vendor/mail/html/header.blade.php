@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'FitPeek')
<img src="{{ config('app.url') }}/images/logo_fitpeek.png" class="logo" alt="FitPeek Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
