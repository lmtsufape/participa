@props([
    'title',
    'description' => null,
    'icon' => 'bi-grid',
])

<style>
    .form-section-card {
        background: #ffffff;
        border: 1px solid #e8ecef;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 18px;

        box-shadow:
            0 2px 4px rgba(15, 23, 42, 0.02),
            0 6px 18px rgba(15, 23, 42, 0.04);
    }

    .form-section-header {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 20px;
    }

    .form-section-icon {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 11px;

        background: #dff5f3;
        color: #087f7a;

        font-size: 1.2rem;
    }

    .form-section-title {
        margin: 0;

        font-size: 1.15rem;
        font-weight: 700;

        color: #182433;
    }

    .form-section-description {
        margin: 3px 0 0;

        font-size: 0.875rem;

        color: #6c757d;
    }

    .form-section-body {
        width: 100%;
    }

    @media (max-width: 768px) {
        .form-section-card {
            padding: 16px;
            border-radius: 12px;
        }

        .form-section-header {
            margin-bottom: 16px;
        }

        .form-section-icon {
            width: 38px;
            height: 38px;
        }
    }
</style>

<section {{ $attributes->merge(['class' => 'form-section-card']) }}>

    <div class="form-section-header">
        <div class="form-section-icon">
            <i class="bi {{ $icon }}"></i>
        </div>

        <div>
            <h5 class="form-section-title">
                {{ $title }}
            </h5>

            @if ($description)
                <p class="form-section-description">
                    {{ $description }}
                </p>
            @endif
        </div>
    </div>

    <div class="form-section-body">
        {{ $slot }}
    </div>

</section>