@once
    <style>
        .app-table-card {
            border-radius: 0.75rem;
        }

        .app-table {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: #f8fafc;
        }

        @media (min-width: 992px) {
            .app-table-wrapper {
                overflow: visible;
            }
        }

        @media (max-width: 991.98px) {
            .app-table-wrapper {
                overflow-x: auto;
                overflow-y: auto;
            }
        }
    </style>
@endonce

<div class="card border-0 shadow-sm app-table-card">
    <div class="app-table-wrapper">
        <table {{ $attributes->class(['table', 'app-table', 'align-middle', 'mb-0']) }}>
            {{ $slot }}
        </table>
    </div>
</div>
