@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'SEML')
{{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="SEML"> --}}
<h2>SEML</h2>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
