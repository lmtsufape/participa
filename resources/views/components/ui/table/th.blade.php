@once
    <style>
        .app-table-th {
            padding: 0.9rem 1rem !important;
            background-color: #f8f9fa !important;
            border-top: 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            color: #6c757d !important;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .app-table thead th:first-child {
            border-top-left-radius: 0.75rem;
        }

        .app-table thead th:last-child {
            border-top-right-radius: 0.75rem;
        }
    </style>
@endonce

<th scope="col" {{ $attributes->class(['app-table-th']) }}>
    {{ $slot }}
</th>
