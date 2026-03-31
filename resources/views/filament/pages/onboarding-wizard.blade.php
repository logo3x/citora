<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('business-created', () => {
                // Confetti explosion
                const duration = 3000;
                const end = Date.now() + duration;

                (function frame() {
                    confetti({
                        particleCount: 5,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 },
                        colors: ['#f59e0b', '#d97706', '#fbbf24', '#10b981', '#3b82f6']
                    });
                    confetti({
                        particleCount: 5,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 },
                        colors: ['#f59e0b', '#d97706', '#fbbf24', '#10b981', '#3b82f6']
                    });

                    if (Date.now() < end) {
                        requestAnimationFrame(frame);
                    }
                }());

                Swal.fire({
                    icon: 'success',
                    title: '🎉 ¡Felicidades!',
                    html: '<p class="text-lg">Tu negocio ha sido creado exitosamente.</p><p class="text-gray-500 mt-2">Ahora puedes gestionar tus servicios, empleados y citas.</p>',
                    confirmButtonText: 'Ir al panel',
                    confirmButtonColor: '#f59e0b',
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp animate__faster'
                    }
                }).then(() => {
                    window.location.href = '{{ filament()->getUrl() }}';
                });
            });
        });
    </script>
</x-filament-panels::page>
