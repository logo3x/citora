<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background:#FAFAF8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;color:#111;line-height:1.6">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#FAFAF8">
        <tr>
            <td align="center" style="padding:32px 16px">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;background:#FFFFFF;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08)">

                    <tr>
                        <td style="padding:24px 32px;background:linear-gradient(135deg,#D97706,#F59E0B);text-align:left">
                            <span style="color:#FFFFFF;font-size:22px;font-weight:700;letter-spacing:-0.02em">Citora</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px">
                            {!! \Illuminate\Support\Str::markdown($bodyMarkdown) !!}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px;background:#F8F4EC;border-top:1px solid #E7E5DF;font-size:12px;color:#64748B;text-align:center;line-height:1.6">
                            Recibes este correo porque tienes una cuenta en
                            <a href="{{ config('app.url') }}" style="color:#D97706;text-decoration:none">Citora</a>.<br>
                            ¿Ya no quieres recibirlos? <a href="{{ $unsubscribeUrl }}" style="color:#64748B;text-decoration:underline">Cancelar suscripción</a>
                        </td>
                    </tr>
                </table>
                @if ($pixelUrl)
                    <img src="{{ $pixelUrl }}" alt="" width="1" height="1" style="display:block;border:0;width:1px;height:1px">
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
