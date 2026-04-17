@extends('legal.layout', [
    'title' => 'Términos y Condiciones — Citora',
    'description' => 'Condiciones de uso de la plataforma Citora.',
])

@section('content')
    <h1 class="page-title">Términos y Condiciones</h1>
    <p class="page-subtitle">Vigentes desde el {{ \Carbon\Carbon::parse($legal['policy']['effective_date'])->translatedFormat('d \\d\\e F \\d\\e Y') }} · Última actualización: {{ \Carbon\Carbon::parse($legal['policy']['last_updated'])->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>

    <div class="meta-box">
        <div><strong>Marca:</strong> {{ $legal['responsible']['brand'] }}</div>
        <div><strong>Sitio web:</strong> <a href="{{ $legal['responsible']['website'] }}" style="color:var(--amber)">{{ $legal['responsible']['website'] }}</a></div>
        <div><strong>Ciudad:</strong> {{ $legal['responsible']['city'] }}, {{ $legal['responsible']['country'] }}</div>
        <div><strong>Contacto:</strong> <a href="mailto:{{ $legal['responsible']['email'] }}" style="color:var(--amber)">{{ $legal['responsible']['email'] }}</a></div>
    </div>

    <div class="content">
        <h2>1. Aceptación de los términos</h2>
        <p>
            Estos Términos y Condiciones (en adelante, los <strong>"Términos"</strong>) regulan el acceso y uso de la plataforma <strong>{{ $legal['responsible']['brand'] }}</strong> ({{ $legal['responsible']['website'] }}), operada desde {{ $legal['responsible']['city'] }}, {{ $legal['responsible']['country'] }} por la persona natural responsable de la marca (en adelante, el <strong>"Operador"</strong>).
        </p>
        <p>
            La identificación completa del Operador puede ser solicitada por escrito al correo <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>.
        </p>
        <p>
            Al registrarte, usar o acceder a {{ $legal['responsible']['brand'] }}, declaras haber leído, entendido y aceptado estos Términos en su totalidad. Si no estás de acuerdo, debes abstenerte de utilizar la plataforma.
        </p>

        <h2>2. Descripción del servicio</h2>
        <p>
            {{ $legal['responsible']['brand'] }} es una plataforma <strong>SaaS (Software como Servicio)</strong> que permite a negocios de servicios (barberías, peluquerías, salones de belleza, spas, profesionales independientes y similares) gestionar su agenda en línea y recibir reservas de citas por parte de sus clientes finales. La plataforma ofrece:
        </p>
        <ul>
            <li>Página pública de reservas para cada negocio registrado.</li>
            <li>Gestión de servicios, empleados y horarios.</li>
            <li>Notificaciones automáticas por SMS y/o WhatsApp a clientes y profesionales.</li>
            <li>Panel administrativo para gestionar citas, reprogramaciones y cancelaciones.</li>
            <li>Procesamiento de pagos de suscripciones a través de la pasarela Wompi.</li>
        </ul>

        <h2>3. Usuarios</h2>
        <p>{{ $legal['responsible']['brand'] }} reconoce dos tipos de usuarios:</p>
        <ul>
            <li><strong>Negocio:</strong> persona natural o jurídica que registra su establecimiento en la plataforma para recibir reservas de citas.</li>
            <li><strong>Cliente final:</strong> persona que agenda una cita en la página pública de un negocio registrado.</li>
        </ul>

        <h2>4. Registro y cuenta</h2>
        <ol>
            <li>Para crear una cuenta debes ser mayor de 18 años y tener capacidad legal para contratar.</li>
            <li>Te comprometes a proporcionar información veraz, completa y actualizada.</li>
            <li>Eres responsable de mantener la confidencialidad de tus credenciales y de toda actividad realizada desde tu cuenta.</li>
            <li>Debes notificar inmediatamente cualquier uso no autorizado de tu cuenta a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>.</li>
            <li>El Operador se reserva el derecho de suspender o cancelar cuentas que incumplan estos Términos.</li>
        </ol>

        <h2>5. Uso permitido</h2>
        <p>Te comprometes a utilizar {{ $legal['responsible']['brand'] }} de forma lícita, respetando los derechos de terceros. Queda prohibido:</p>
        <ul>
            <li>Usar la plataforma para actividades ilegales, fraudulentas o contrarias a la moral y las buenas costumbres.</li>
            <li>Comercializar productos o servicios prohibidos por la ley colombiana (drogas, armas, pornografía, etc.).</li>
            <li>Publicar información falsa, engañosa o que induzca a error a otros usuarios.</li>
            <li>Intentar vulnerar la seguridad del sistema, realizar ingeniería inversa, scraping masivo o ataques de denegación de servicio.</li>
            <li>Utilizar la plataforma para enviar spam o comunicaciones no solicitadas.</li>
            <li>Suplantar la identidad de otra persona o negocio.</li>
        </ul>

        <h2>6. Responsabilidad del negocio</h2>
        <p>El negocio registrado es el <strong>único responsable</strong> de:</p>
        <ul>
            <li>La calidad, seguridad y legalidad de los servicios que ofrece a través de {{ $legal['responsible']['brand'] }}.</li>
            <li>Cumplir con las citas agendadas y atender a sus clientes de forma profesional.</li>
            <li>Cumplir con las obligaciones tributarias, laborales y regulatorias aplicables a su actividad.</li>
            <li>Obtener el consentimiento de sus empleados para registrar sus datos en la plataforma.</li>
            <li>Gestionar los reclamos y disputas con sus clientes finales.</li>
        </ul>
        <p>
            {{ $legal['responsible']['brand'] }} <strong>no es responsable</strong> de la prestación de los servicios que los negocios ofrecen a través de la plataforma, ni de los acuerdos, conflictos o disputas que surjan entre negocios y sus clientes.
        </p>

        <h2>7. Responsabilidad del cliente final</h2>
        <p>El cliente final que agenda una cita se compromete a:</p>
        <ul>
            <li>Proporcionar información veraz al momento de reservar.</li>
            <li>Asistir puntualmente a la cita o cancelar con antelación razonable.</li>
            <li>Respetar al profesional y al establecimiento.</li>
            <li>Pagar el servicio contratado directamente al negocio (salvo que el negocio indique pago en línea).</li>
        </ul>

        <h2>8. Notificaciones por SMS y WhatsApp</h2>
        <p>
            Al proporcionar un número de celular al registrarte o al agendar una cita, <strong>autorizas expresamente</strong> a {{ $legal['responsible']['brand'] }} a enviar notificaciones transaccionales relacionadas con tus citas, incluyendo:
        </p>
        <ul>
            <li>Confirmación de reserva.</li>
            <li>Recordatorios antes de la cita.</li>
            <li>Notificaciones de cancelación o reprogramación.</li>
            <li>Mensajes relacionados con el estado de tu cuenta o suscripción.</li>
        </ul>
        <p>
            Estas comunicaciones son <strong>estrictamente transaccionales</strong>, no incluyen publicidad ni promociones no solicitadas. Puedes solicitar dejar de recibirlas eliminando tu cuenta o escribiendo a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>.
        </p>

        <h2>9. Planes, pagos y suscripciones</h2>
        <p>
            {{ $legal['responsible']['brand'] }} ofrece planes de suscripción para negocios que deseen acceder a funcionalidades avanzadas. Los planes vigentes, sus precios y condiciones se muestran en la plataforma al momento de la contratación.
        </p>
        <ul>
            <li>Los pagos se procesan a través de <strong>Wompi</strong>, pasarela autorizada en Colombia.</li>
            <li>Los precios incluyen o excluyen IVA según se indique en el momento del pago.</li>
            <li>La activación de funcionalidades es automática tras la confirmación del pago.</li>
            <li>La suscripción no se renueva automáticamente salvo indicación expresa en el momento de la contratación.</li>
        </ul>

        <div class="callout">
            <strong>Política de reembolsos:</strong> las suscripciones a {{ $legal['responsible']['brand'] }} <strong>no son reembolsables</strong> una vez activadas. Ante cualquier inconveniente, contáctanos y buscaremos resolverlo a la mayor brevedad.
        </div>

        <h2>10. Propiedad intelectual</h2>
        <p>
            La marca <strong>{{ $legal['responsible']['brand'] }}</strong>, el logotipo, el diseño de la plataforma, el código fuente, la documentación y demás elementos que componen el sitio son propiedad exclusiva del Operador y están protegidos por las leyes de propiedad intelectual de Colombia e internacionales.
        </p>
        <p>
            El contenido que tú publiques (nombre del negocio, fotografías, descripciones, etc.) sigue siendo de tu propiedad, pero nos concedes una <strong>licencia no exclusiva, mundial y gratuita</strong> para mostrarlo dentro de la plataforma con el fin de prestar el servicio.
        </p>

        <h2>11. Disponibilidad del servicio</h2>
        <p>
            Aunque nos esforzamos por mantener {{ $legal['responsible']['brand'] }} disponible 24/7, el Operador no garantiza que la plataforma estará libre de interrupciones, errores o fallos técnicos. Podemos realizar mantenimientos programados o de emergencia sin previo aviso.
        </p>
        <p>
            El Operador no será responsable por daños derivados de la indisponibilidad temporal del servicio, pérdida de datos por causas ajenas a su control, o fallas en proveedores externos (hosting, pasarela de pagos, proveedores de mensajería).
        </p>

        <h2>12. Limitación de responsabilidad</h2>
        <p>En la máxima medida permitida por la ley:</p>
        <ul>
            <li>El Operador no será responsable por daños indirectos, incidentales, lucro cesante, pérdida de oportunidades o pérdida de datos.</li>
            <li>La responsabilidad total del Operador, en cualquier caso, no excederá el monto que hayas pagado por el servicio en los <strong>12 meses</strong> anteriores al hecho que generó la responsabilidad.</li>
        </ul>

        <h2>13. Suspensión y terminación</h2>
        <p>El Operador podrá suspender o cancelar tu cuenta en los siguientes casos:</p>
        <ul>
            <li>Incumplimiento grave de estos Términos.</li>
            <li>Uso fraudulento de la plataforma.</li>
            <li>Solicitud de autoridades competentes.</li>
            <li>Falta de pago de suscripciones activas.</li>
        </ul>
        <p>
            Puedes cancelar tu cuenta en cualquier momento desde la sección correspondiente de la plataforma o escribiendo a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>. Tras la cancelación, conservaremos tus datos según lo descrito en nuestra <a href="{{ route('legal.privacy') }}">Política de Privacidad</a>.
        </p>

        <h2>14. Modificaciones</h2>
        <p>
            El Operador puede modificar estos Términos en cualquier momento. Los cambios significativos serán notificados por correo electrónico o mediante aviso destacado en la plataforma con al menos <strong>15 días de antelación</strong>. El uso continuado de la plataforma tras la entrada en vigor de los cambios implica tu aceptación.
        </p>

        <h2>15. Ley aplicable y jurisdicción</h2>
        <p>
            Estos Términos se rigen por las leyes de la <strong>República de Colombia</strong>. Cualquier controversia derivada de la relación entre el usuario y el Operador será resuelta por los jueces competentes de la ciudad de {{ $legal['responsible']['city'] }}, {{ $legal['responsible']['state'] }}, Colombia, renunciando las partes a cualquier otro fuero que pudiera corresponderles.
        </p>

        <h2>16. Contacto</h2>
        <p>
            Para cualquier duda, notificación o reclamo relacionado con estos Términos, escríbenos a <a href="mailto:{{ $legal['responsible']['email'] }}">{{ $legal['responsible']['email'] }}</a>.
        </p>
    </div>
@endsection
