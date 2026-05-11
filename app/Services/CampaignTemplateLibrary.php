<?php

namespace App\Services;

class CampaignTemplateLibrary
{
    /**
     * @return array<int, array{key: string, label: string, segment: string, subject: string, body: string}>
     */
    public static function all(): array
    {
        return [
            [
                'key' => 'welcome_first_week',
                'label' => '👋 Bienvenida primera semana',
                'segment' => UserSegmentResolver::SEGMENT_NEW_LAST_7D,
                'subject' => '¡Bienvenido a Citora! Aquí va lo que sigue 👇',
                'body' => <<<'MD'
                ¡Qué bueno verte por aquí! 👋

                Soy del equipo de **Citora** y te quería compartir 3 pasos rápidos para que aproveches la plataforma desde el primer día:

                ## 1. Crea tu primer servicio
                Entra al menú **Servicios** y registra al menos uno (corte, manicura, masaje, lo que ofreces). **No olvides asignarle empleados** — sin eso, nadie podrá reservarlo.

                ## 2. Comparte tu link público
                Tu negocio ya tiene una URL del tipo `citora.com.co/tu-slug`. Compártela en Instagram, WhatsApp, donde quieras. Tus clientes reservan **sin descargar nada**.

                ## 3. Revisa tu calendario
                Mira el menú **Calendario** para ver tus citas de forma visual. Cuando alguien reserve, te llega WhatsApp + email automático.

                Cualquier duda, responde este correo y te ayudo. 🙌

                Un abrazo,
                El equipo de Citora
                MD,
            ],
            [
                'key' => 'inactive_7d',
                'label' => '🔥 Inactivos 7 días sin crear cita',
                'segment' => UserSegmentResolver::SEGMENT_INACTIVE_7D,
                'subject' => '¿Cómo te ha ido con Citora? 🤔',
                'body' => <<<'MD'
                Hola 👋

                Vi que tu negocio está en Citora pero llevas unos días sin crear citas. ¿Todo bien?

                Si te quedaste pegado en algún paso o necesitas ayuda para arrancar, **responde este correo** y te ayudo personalmente. A veces es un detalle pequeño:

                - ¿Empleados sin servicios asignados?
                - ¿Horarios no configurados?
                - ¿No sabes cómo compartir tu link?

                También puedes volver a ver el **tutorial guiado** desde el menú *Mi negocio → Ver tutorial de nuevo*.

                Quedo atento.
                MD,
            ],
            [
                'key' => 'near_limit',
                'label' => '🎯 Cerca del límite del plan',
                'segment' => UserSegmentResolver::SEGMENT_NEAR_LIMIT,
                'subject' => '🎯 Estás cerca del límite mensual',
                'body' => <<<'MD'
                ¡Hola!

                Buenas noticias: tu negocio en **Citora** está creciendo. Ya usaste más del **80% de tus citas del mes** en el plan gratuito.

                ## ¿Qué pasa cuando llegues al 100%?
                Tus clientes verán un mensaje indicando que el negocio no puede aceptar más reservas este mes.

                ## Tu opción
                Activa un plan pago y obtén:
                - Citas **ilimitadas** este mes
                - Recordatorios automáticos por WhatsApp
                - Más empleados sin restricción
                - Sin marca "powered by Citora" en los correos

                Si quieres seguir creciendo sin frenos, [activa tu plan aquí](https://citora.com.co/admin).

                Cualquier duda, aquí estoy. 💪
                MD,
            ],
            [
                'key' => 'feature_announce',
                'label' => '🚀 Anuncio de nueva función',
                'segment' => UserSegmentResolver::SEGMENT_ALL,
                'subject' => '🚀 Acabamos de lanzar algo que te va a gustar',
                'body' => <<<'MD'
                Hola 👋

                Estuvimos trabajando en algo nuevo y queríamos contártelo de primero porque sabemos que te puede servir.

                ## ✨ [Nombre de la función]
                Describe aquí en 2-3 líneas qué hace la función y qué problema resuelve.

                ## ¿Cómo usarla?
                1. Entra a `https://citora.com.co/admin`
                2. Ve al menú **[Sección]**
                3. Click en **[Botón]**

                Está disponible para todos los planes desde hoy. Pruébala y cuéntanos qué tal — tu feedback nos guía para construir lo siguiente.

                ¡Gracias por confiar en Citora!
                MD,
            ],
            [
                'key' => 'inactive_30d',
                'label' => '💤 Inactivos 30 días — reactivación',
                'segment' => UserSegmentResolver::SEGMENT_INACTIVE_30D,
                'subject' => 'Te extrañamos en Citora 💛',
                'body' => <<<'MD'
                Hola 👋

                Notamos que llevas un tiempo sin entrar a Citora. Sin presión — sé que el día a día se llena rápido.

                Solo quería recordarte un par de cosas:

                ## Tu agenda sigue funcionando
                Aunque no entres, tus clientes pueden seguir reservando en `citora.com.co/tu-negocio`. Los recordatorios automáticos por WhatsApp siguen activos.

                ## Si algo te frenó, dime
                A veces hay un detalle pequeño que bloquea el flujo (un servicio sin empleado, un horario mal puesto). **Responde este correo** y lo miramos juntos.

                Aquí estamos cuando quieras volver. 🙌
                MD,
            ],
            [
                'key' => 'usage_reminder',
                'label' => '📈 Recordatorio de uso',
                'segment' => UserSegmentResolver::SEGMENT_ALL,
                'subject' => '📈 Tu agenda esta semana en Citora',
                'body' => <<<'MD'
                Hola,

                Te paso un resumen rápido de lo que vimos en tu Citora esta semana 👇

                ## 💡 Tips para esta semana
                - Comparte tu link al final de cada conversación de WhatsApp
                - Pide a clientes recurrentes que reserven solos por la web (te ahorra tiempo)
                - Revisa el **Dashboard** para ver tus ingresos del mes

                ## ¿Sabías que...?
                Puedes **exportar tus citas a Excel** desde el menú *Citas → Exportar CSV*. Útil para fin de mes.

                Sigue así. 💪
                MD,
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        foreach (self::all() as $tpl) {
            if ($tpl['key'] === $key) {
                return $tpl;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $opts = [];
        foreach (self::all() as $tpl) {
            $opts[$tpl['key']] = $tpl['label'];
        }

        return $opts;
    }
}
