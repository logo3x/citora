<div style="width:100%">
    <div style="position:relative;display:flex;align-items:center;justify-content:center;margin:20px 0">
        <div style="position:absolute;inset:0;display:flex;align-items:center">
            <div style="width:100%;border-top:1px solid #e5e7eb"></div>
        </div>
        <div style="position:relative;padding:0 16px;font-size:12px;font-weight:500;color:#9ca3af;background:white;text-transform:uppercase;letter-spacing:0.05em">
            o continúa con
        </div>
    </div>

    <a href="{{ route('auth.google.redirect') }}"
       style="display:flex;align-items:center;justify-content:center;width:100%;gap:10px;padding:12px 20px;font-size:14px;font-weight:600;color:#374151;background:white;border:1px solid #e5e7eb;border-radius:12px;text-decoration:none;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.05)"
       onmouseover="this.style.background='#f9fafb';this.style.borderColor='#d1d5db'"
       onmouseout="this.style.background='white';this.style.borderColor='#e5e7eb'">
        <svg style="width:20px;height:20px" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Google
    </a>

    <p style="text-align:center;font-size:12px;color:#9ca3af;margin-top:16px">
        Al continuar, aceptas los <a href="#" style="text-decoration:underline">términos</a> y la <a href="#" style="text-decoration:underline">política de privacidad</a>
    </p>
</div>
