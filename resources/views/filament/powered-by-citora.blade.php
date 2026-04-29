@php
    /** @var \App\Models\Business|null $business */
    $business = auth()->user()?->business;
    $hasOwnLogo = $business && $business->hasMedia('logo');
@endphp

@if ($hasOwnLogo)
    <div style="padding: 6px 16px 12px; font-size: 11px; color: rgb(107 114 128); text-align: center; line-height: 1.3;">
        Powered by
        <a href="https://citora.com.co" target="_blank" rel="noopener" style="color: rgb(217 119 6); font-weight: 600; text-decoration: none;">
            citora.com.co
        </a>
    </div>
@endif
