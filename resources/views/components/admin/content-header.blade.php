@props([
    'title',
    'description' => null,
    'href' => null,
    'buttonText' => null,
    'icon' => 'bi bi-plus-lg',
    'buttonClass' => 'btn-my-primary',
])

<div {{ $attributes->merge(['class' => 'row align-items-start mb-4']) }}>
    <div class="col-12 col-lg-8">
        <h1 class="display-6 fw-bold text-dark mb-2">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="text-secondary fs-6 mb-0">
                {{ $description }}
            </p>
        @endif
    </div>

    @if ($href && $buttonText)
        <div class="col-12 col-lg-4 mt-3 mt-lg-4 text-lg-end">
            <a href="{{ $href }}"
               class="btn {{ $buttonClass }} d-inline-flex align-items-center gap-2 px-4">
                @if ($icon)
                    <i class="{{ $icon }}"></i>
                @endif

                <span>{{ $buttonText }}</span>
            </a>
        </div>
    @endif
</div>
