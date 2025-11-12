@props([
    'placeholder',
    'value',
    'route',
    'txt_btn',
    'aria_label_btn'
])

<form id="search-box" method="GET" class="mb-3">
    <div class="input-group">
        <input type="search" name="filter[q]" value="{{ request('filter.q') }}"
            class="form-control" placeholder="{{ $placeholder }}">
        <button class="btn btn-outline-primary" type="submit" aria-label="{{ $aria_label_btn }}">
            <i class="bi bi-search" aria-hidden="true"></i>
            <span class="ms-1">{{ $txt_btn }}</span>
        </button>
    </div>
</form>