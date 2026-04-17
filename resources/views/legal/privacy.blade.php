@extends('legal.layout', [
    'title' => 'Política de Privacidad — Citora',
    'description' => 'Cómo Citora protege y trata tus datos personales.',
])

@section('content')
    <h1 class="page-title">Política de Privacidad</h1>
    <p class="page-subtitle">Vigente desde el {{ \Carbon\Carbon::parse($legal['policy']['effective_date'])->translatedFormat('d \\d\\e F \\d\\e Y') }} · Última actualización: {{ \Carbon\Carbon::parse($legal['policy']['last_updated'])->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>

    <div class="meta-box">
        <div><strong>Responsable:</strong> {{ $legal['responsible']['brand'] }}</div>
        <div><strong>Ciudad:</strong> {{ $legal['responsible']['city'] }}, {{ $legal['responsible']['country'] }}</div>
        <div><strong>Contacto:</strong> <a href="mailto:{{ $legal['responsible']['email'] }}" style="color:var(--amber)">{{ $legal['responsible']['email'] }}</a></div>
    </div>

    <div class="content">
        <h2>1. Introducción</h2>
        <p>
            En <strong>{{ $legal['responsible']['brand'] }}</strong> ({{ $legal['responsible']['website'] }}) respetamos tu privacidad y nos tomamos en serio la protección de tus datos personales. Esta Política de Privacidad explica qué información recopilamos, cómo la usamos, con quién la compartimos y qué derechos tienes sobre ella, en cumplimiento de la <strong>Ley 1581 de 2012</strong> y el <strong>Decreto 1377 de 2013</strong> de la República de Colombia.
        </p>
        <p>
            Al utilizar nuestros servicios aceptas las prácticas descritas en esta Política. Si no estás de acuerdo con alguno de los términos, por favor no uses la plataforma.
        </p>

        <h2>2. Responsable del tratamiento</h2>
        <p>
            El responsable del tratamiento de los datos personales recolectados a través de la plataforma <strong>{{ $legal['responsible']['brand'] }}</strong> es la persona natural que opera la marca, con domicilio en {{ $legal['responsible']['city'] }}, {{ $legal['responsible']['state'] }}, {{ $legal['responsible']['country'] }}.
        </p>
        <p>
            La identificación completa del responsable (nombre, documento de identidad y dirección) puede ser solicitada por escrito en cualquier momento al correo <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>, indicando el motivo de la solicitud. Esta información será entregada de forma inmediata ante solicitudes legítimas de titulares de datos o autoridades competentes.
        </p>

        <h2>3. Qué datos recopilamos</h2>
        <p>Dependiendo del uso que hagas de {{ $legal['responsible']['brand'] }}, podemos recolectar los siguientes datos:</p>

        <h3>3.1 Datos proporcionados por ti</h3>
        <ul>
            <li><strong>Datos de identificación:</strong> nombre completo.</li>
            <li><strong>Datos de contacto:</strong> correo electrónico y número de celular.</li>
            <li><strong>Datos del negocio</strong> (si registras uno): nombre comercial, dirección, servicios ofrecidos, horarios, fotografías e información de empleados.</li>
            <li><strong>Datos de reserva:</strong> fecha, hora, servicio seleccionado, notas adicionales.</li>
            <li><strong>Datos de pago</strong> (para suscripciones): procesados directamente por la pasarela Wompi. No almacenamos información de tarjetas de crédito.</li>
        </ul>

        <h3>3.2 Datos recopilados automáticamente</h3>
        <ul>
            <li>Dirección IP, tipo de navegador y dispositivo.</li>
            <li>Páginas visitadas dentro de {{ $legal['responsible']['brand'] }}, fecha y duración de la visita.</li>
            <li>Identificadores de sesión y cookies funcionales.</li>
        </ul>

        <h2>4. Finalidades del tratamiento</h2>
        <p>Los datos personales que recolectamos son utilizados exclusivamente para:</p>
        <ol>
            <li>Gestionar y confirmar reservas de citas entre clientes y negocios.</li>
            <li>Enviar notificaciones transaccionales relacionadas con tus citas: confirmaciones, recordatorios, cancelaciones y reprogramaciones, a través de <strong>SMS</strong> o <strong>WhatsApp</strong> al número que nos hayas proporcionado.</li>
            <li>Administrar la cuenta del usuario y el perfil del negocio.</li>
            <li>Procesar pagos de suscripción a través de proveedores de pago autorizados.</li>
            <li>Prevenir fraudes, abusos y garantizar la seguridad de la plataforma.</li>
            <li>Atender solicitudes, consultas y reclamos.</li>
            <li>Cumplir obligaciones legales, contables y tributarias.</li>
        </ol>

        <div class="callout">
            <strong>No enviamos mensajes publicitarios ni promocionales no solicitados.</strong> Solo recibirás comunicaciones directamente relacionadas con las citas que tú o tus clientes hayan agendado.
        </div>

        <h2>5. Base legal del tratamiento</h2>
        <p>El tratamiento de tus datos personales se basa en:</p>
        <ul>
            <li>Tu <strong>consentimiento expreso</strong> al momento de registrarte o reservar una cita.</li>
            <li>La <strong>ejecución del contrato</strong> de servicio (prestarte la funcionalidad de {{ $legal['responsible']['brand'] }}).</li>
            <li>El cumplimiento de <strong>obligaciones legales</strong> aplicables.</li>
        </ul>

        <h2>6. Con quién compartimos tus datos</h2>
        <p>No vendemos ni alquilamos tus datos personales. Los compartimos únicamente con:</p>
        <ul>
            <li><strong>Proveedores de infraestructura:</strong> {{ $legal['hosting']['provider'] }} ({{ $legal['hosting']['country'] }}), que aloja nuestros servidores.</li>
            <li><strong>Proveedores de mensajería:</strong> Twilio y/o proveedores locales colombianos autorizados, para entregar los SMS y mensajes de WhatsApp transaccionales.</li>
            <li><strong>Pasarela de pagos:</strong> Wompi, para procesar pagos de suscripción.</li>
            <li><strong>Servicios de autenticación:</strong> Google, cuando optas por iniciar sesión con tu cuenta de Google.</li>
            <li><strong>Autoridades competentes:</strong> cuando la ley lo exija mediante requerimiento formal.</li>
        </ul>
        <p>Todos nuestros proveedores están obligados a proteger tu información y a no usarla para fines distintos a los contratados.</p>

        <h2>7. Transferencia internacional</h2>
        <p>
            Algunos de nuestros proveedores (Twilio, Google, Wompi) operan desde fuera de Colombia. Al aceptar esta política autorizas la transferencia internacional de tus datos a dichos proveedores, quienes cuentan con niveles adecuados de protección conforme a los estándares internacionales.
        </p>

        <h2>8. Retención de datos</h2>
        <p>
            Conservaremos tus datos personales mientras tu cuenta esté activa y, posteriormente, por un período de <strong>{{ $legal['policy']['retention_years'] }} años</strong> contados a partir de la eliminación o inactivación de la cuenta, con el fin de cumplir obligaciones legales, contables y tributarias vigentes en Colombia.
        </p>
        <p>Una vez transcurrido este plazo, tus datos serán eliminados o anonimizados de forma segura.</p>

        <h2>9. Tus derechos (Habeas Data)</h2>
        <p>De acuerdo con la Ley 1581 de 2012, como titular de tus datos personales tienes derecho a:</p>
        <ul>
            <li><strong>Conocer, actualizar y rectificar</strong> tus datos personales.</li>
            <li><strong>Solicitar prueba</strong> de la autorización otorgada.</li>
            <li><strong>Ser informado</strong> sobre el uso dado a tus datos.</li>
            <li><strong>Presentar quejas</strong> ante la Superintendencia de Industria y Comercio (SIC) por infracciones.</li>
            <li><strong>Revocar la autorización</strong> y solicitar la supresión de tus datos, salvo cuando exista una obligación legal de conservarlos.</li>
            <li><strong>Acceder gratuitamente</strong> a tus datos tratados.</li>
        </ul>
        <p>
            Para ejercer cualquiera de estos derechos, envíanos un correo a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a> con el asunto <em>"Solicitud Habeas Data"</em>, incluyendo tu nombre completo, documento de identidad y una descripción clara de tu solicitud. Responderemos en un plazo máximo de <strong>15 días hábiles</strong>.
        </p>

        <h2>10. Seguridad</h2>
        <p>
            Implementamos medidas técnicas, administrativas y humanas razonables para proteger tus datos contra pérdida, acceso no autorizado, alteración o divulgación. Utilizamos conexiones seguras (HTTPS), contraseñas cifradas, copias de respaldo y control de acceso basado en roles.
        </p>
        <p>
            Sin embargo, ningún sistema de transmisión o almacenamiento es 100% seguro. Te recomendamos mantener la confidencialidad de tus credenciales de acceso.
        </p>

        <h2>11. Cookies</h2>
        <p>{{ $legal['responsible']['brand'] }} utiliza cookies estrictamente necesarias para el funcionamiento del sitio (sesión, autenticación, preferencias de navegación). No usamos cookies de terceros con fines publicitarios.</p>

        <h2>12. Menores de edad</h2>
        <p>{{ $legal['responsible']['brand'] }} no está dirigida a menores de 18 años. Si tienes conocimiento de que un menor de edad nos ha proporcionado datos sin autorización de sus padres o tutores, por favor contáctanos para eliminar la información.</p>

        <h2>13. Cambios a esta Política</h2>
        <p>
            Podemos actualizar esta Política de Privacidad cuando sea necesario por cambios en la ley, en nuestros servicios o en nuestras prácticas. La fecha de última actualización se indica al inicio del documento. Los cambios relevantes serán notificados a través del correo electrónico registrado o mediante aviso destacado en el sitio.
        </p>

        <h2>14. Contacto</h2>
        <p>
            Si tienes preguntas sobre esta Política de Privacidad o sobre el tratamiento de tus datos personales, escríbenos a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>.
        </p>
    </div>
@endsection
