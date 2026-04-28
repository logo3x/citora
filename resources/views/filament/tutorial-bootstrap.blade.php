@php
    $user = auth()->user();
    $tutorialCompleted = $user?->tutorial_completed_at !== null;
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    window.CitoraTutorial = {
        completed: {{ $tutorialCompleted ? 'true' : 'false' }},
    };
</script>
<script src="/js/citora-tour.js?v=1"></script>
