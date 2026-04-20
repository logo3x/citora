{{-- Google Tag Manager (body noscript) --}}
@if ($gtm = config('services.gtm.container_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
{{-- End Google Tag Manager (body noscript) --}}
