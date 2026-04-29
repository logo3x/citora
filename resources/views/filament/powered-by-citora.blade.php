@php
    /** @var \App\Models\Business|null $business */
    $business = auth()->user()?->business;
    $hasOwnLogo = $business && $business->hasMedia('logo');
@endphp

@if ($hasOwnLogo)
    <div style="margin: -6px 0 6px; padding: 0 12px; font-size: 10px; color: rgb(148 163 184); text-align: center; line-height: 1.2;">
        Powered by
        <a href="https://citora.com.co" target="_blank" rel="noopener" style="color: rgb(217 119 6); font-weight: 500; text-decoration: none;">
            citora.com.co
        </a>
    </div>
@endif
