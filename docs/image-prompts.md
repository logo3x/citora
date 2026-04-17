# Prompts para generar imágenes de Citora

Estos prompts están optimizados para generadores de imágenes tipo **Midjourney v6+**, **DALL·E 3**, **Stable Diffusion XL**, **Flux** o **Ideogram**. Todos respetan la identidad visual actual de Citora: tonos **cream (`#FAFAF8`)**, **slate-900 (`#0F172A`)**, **amber (`#D97706`)** y tipografía limpia tipo Inter/Poppins.

## 🎨 Paleta de marca (referencia para todos los prompts)

- **Primario**: Amber `#D97706` / `#F59E0B`
- **Fondo oscuro**: Slate `#0F172A` / `#1E293B`
- **Fondo claro**: Cream `#FAFAF8`
- **Acento**: Teal `#0D9488`
- **Bordes**: `#E7E5DF`

Incluye en cualquier prompt la cláusula: `color palette: warm amber #D97706, deep slate #0F172A, cream #FAFAF8, with subtle teal accents`.

---

## 1. Logo y favicon

### 1.1 Logo claro (`/public/images/logo-light.png`)

```
Minimalist logo for "Citora", a SaaS appointment management platform.
A clean, geometric mark combining a clock hand with a subtle calendar/agenda element.
Warm amber primary color (#D97706), with a slate blue accent (#0F172A).
Flat vector style, rounded corners, premium feel, well balanced negative space.
The wordmark "Citora" in Poppins Bold alongside the icon, slightly rounded letters.
White/transparent background. Scalable, works at 32x32 and at 512x512.
Style: modern, professional, minimal, tech-forward.
```

### 1.2 Logo oscuro (`/public/images/logo-dark.png`)

```
Same "Citora" logo as above but adapted for dark backgrounds.
The amber color remains (#F59E0B, slightly brighter for contrast).
The wordmark "Citora" in white, Poppins Bold.
Transparent background optimized for dark headers and hero sections.
```

### 1.3 Favicon 32x32

```
Square favicon for "Citora" — just the icon mark (no wordmark).
Amber #D97706 on cream #FAFAF8 background, rounded square 32x32.
Minimalist, highly legible at small sizes, works on both light and dark tabs.
```

---

## 2. Hero image de la landing (homepage)

```
Illustration for a SaaS landing page about online appointment booking.
Center composition: a smartphone mockup showing a calendar interface with an amber highlight
on a selected time slot. Around it, floating UI elements: a service card with a small emoji,
a tiny profile avatar, a subtle check-confirmation badge.
Style: clean, airy, slightly isometric, soft shadows, warm amber (#D97706) as main accent,
cream background (#FAFAF8), slate dark (#0F172A) for text inside the mockup.
Mood: professional, friendly, uncluttered, Colombian small-business feel.
No people, no faces, focus on product and abstract UI elements.
Aspect ratio 16:9, high detail, vector-inspired flat illustration with subtle gradients.
```

---

## 3. Imagen decorativa "confirmación" (404 visual / éxito)

```
Simple line illustration of a calendar page with a single amber check-mark circle
on one of the dates. Behind it, two overlapping soft-rounded rectangles suggesting
a notification card and a time selector.
Palette: amber #D97706, slate #0F172A, cream #FAFAF8. Flat design, thin outlines.
Aspect ratio 1:1, minimalist, mobile-friendly, no text.
```

---

## 4. Imagen "bienvenida / onboarding"

```
Flat illustration of an empty agenda or open notebook viewed from above,
with a stylus/pencil hovering over it. Around: floating icons — a small scissors icon,
a tiny mirror shape, a nail-polish bottle outline — representing the diversity of services
(barbershop, beauty salon, spa) Citora serves.
Warm amber accents (#D97706), cream background (#FAFAF8), light paper texture.
Style: modern, minimal, welcoming, suitable for first-time user onboarding screen.
Aspect ratio 4:3, no text.
```

---

## 5. Placeholder de servicio (cuando un negocio no sube imagen)

```
Subtle abstract pattern suitable as placeholder for service images in a booking app.
Composition: soft geometric shapes (circles, arches, rectangles) in layered warm tones —
amber #F59E0B, beige #FAFAF8, hints of soft teal #CCFBF1.
Flat design, calm, non-distracting, no text, no recognizable objects.
Feels premium but neutral enough to not compete with real business photos when they load later.
Aspect ratio 16:9 (960x540px).
```

---

## 6. Banner hero para "Appointment Share" (página `/c/{token}`)

Usado cuando el negocio no tiene banner subido. Neutro y elegante.

```
Abstract horizontal banner for a small service business in Colombia.
Style: gradient from slate #0F172A to slate #1E293B, with subtle amber glow (#D97706)
in the bottom-right corner like a sunrise. A faint pattern of thin curved lines
suggesting schedule/time flow.
No text, no faces, no logos.
Aspect ratio 16:5 (1600x500px), clean, professional, modern SaaS aesthetic.
```

---

## 7. OG image para redes sociales (compartir citora.com.co)

```
Open Graph image 1200x630px for a SaaS landing page.
Left half: the word "Citora" in large bold Poppins font, amber color #D97706 on cream background.
Below the wordmark, a one-line tagline: "Agenda tu cita en segundos" in slate-700.
Right half: soft illustrated smartphone showing a simplified booking interface
with an amber "Confirmada" badge visible.
Clean, professional, warm, readable even as a thumbnail.
No other text, no watermarks.
```

---

## 8. Ilustraciones por estado de cita (opcional — para emails/share page)

### 8.1 Cita confirmada
```
Tiny flat vector illustration: a simple calendar icon with a single green check-mark
badge overlapping the bottom-right corner. Ambient soft shadow.
Colors: green #059669 badge, slate #0F172A calendar outline, cream #FAFAF8 background.
Square 200x200px, no text, minimal, suitable as header icon in a confirmation card.
```

### 8.2 Cita cancelada
```
Tiny flat vector illustration: a calendar icon with a soft red X circle overlapping
one of the dates. Muted, respectful — not alarming.
Colors: red #DC2626 badge (not too saturated), slate calendar, cream background.
Square 200x200px, no text.
```

### 8.3 Cita reprogramada
```
Tiny flat vector illustration: a calendar icon with a circular arrow badge suggesting
"reschedule", placed at the corner. Amber-to-teal gradient on the arrow.
Slate calendar outline, cream background. Square 200x200px, no text.
```

### 8.4 Recordatorio
```
Tiny flat vector illustration: a small bell icon with a clock face in the center,
surrounded by soft radiating lines.
Amber bell #D97706, slate clock hands, cream background. Square 200x200px, no text.
```

---

## 9. Íconos decorativos para páginas legales

### 9.1 Ícono "Privacidad"

```
Small line-art icon representing data privacy and protection.
A shield shape with a subtle padlock silhouette inside, optionally a tiny agenda/calendar
element peeking from behind. Thin strokes, rounded joins.
Amber #D97706 primary line, slate #0F172A for the padlock, cream background.
Square 128x128px, flat, minimal, no text.
```

### 9.2 Ícono "Términos"

```
Small line-art icon representing terms and agreements.
A document/paper icon with three horizontal lines representing text, and a signature
flourish at the bottom.
Thin strokes, amber signature accent #D97706, slate paper outline.
Square 128x128px, flat, minimal, no text.
```

---

## 10. Ilustraciones por vertical (para landing)

Cuando el landing muestre categorías (barberías, salones, spas, etc.).

### 10.1 Barbería
```
Flat minimal illustration of a classic barbershop chair with a small scissors icon
floating nearby. Warm tones: leather brown seat, amber highlight on metal, cream background.
Aspect ratio 1:1, no text, clean, friendly.
```

### 10.2 Salón de belleza
```
Flat minimal illustration of a styling station: round mirror, a hairbrush and a small
bottle of styling product on a shelf. Soft pink and amber tones on cream background.
Aspect ratio 1:1, no text.
```

### 10.3 Spa / estética
```
Flat minimal illustration of a spa table with a rolled white towel, a small stack of
flat stones, and a leaf motif. Tranquil colors: sage green #A7F3D0 and amber accents
on cream background.
Aspect ratio 1:1, no text, calming.
```

### 10.4 Manicure / uñas
```
Flat minimal illustration of a hand with painted nails (top view), next to a bottle
of nail polish and a small file. Amber-orange polish bottle, cream background, soft shadows.
Aspect ratio 1:1, no text, friendly, modern.
```

---

## 📝 Tips para obtener mejores resultados

1. **Empieza con relación de aspecto clara** (`--ar 16:9`, `--ar 1:1`, etc.) para evitar deformaciones.
2. **Pide SVG/vector si el generador lo permite** (DALL·E devuelve bitmap; Ideogram es bueno para logos).
3. **Evita fotos realistas de personas** — complica el cumplimiento legal de imagen de terceros en Colombia y no encaja con el tono ilustrado del landing.
4. **Si una imagen queda con texto basura** (los modelos a veces inventan texto), pide explícitamente "no text, no letters, no words".
5. **Para el logo final**, luego de generarlo, recomiendo vectorizarlo en [vectorizer.ai](https://vectorizer.ai) o Adobe Illustrator para tenerlo en SVG y usarlo en todas partes sin pérdida.
6. **Guarda las imágenes** bajo `public/images/` con nombres descriptivos (`logo-light.png`, `hero-landing.png`, `placeholder-service.png`, etc.).

---

## 📁 Estructura sugerida de carpetas

```
public/images/
├── logo-light.png           # #1.1
├── logo-dark.png            # #1.2
├── favicon.png              # #1.3
├── hero-landing.png         # #2
├── hero-banner.png          # #6
├── og-image.png             # #7
├── placeholder-service.png  # #5
├── illustrations/
│   ├── welcome.png          # #4
│   ├── confirmed.png        # #8.1
│   ├── cancelled.png        # #8.2
│   ├── rescheduled.png      # #8.3
│   └── reminder.png         # #8.4
├── verticals/
│   ├── barber.png           # #10.1
│   ├── salon.png            # #10.2
│   ├── spa.png              # #10.3
│   └── nails.png            # #10.4
└── icons/
    ├── privacy.png          # #9.1
    └── terms.png            # #9.2
```
