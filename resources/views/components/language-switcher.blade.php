<div class="language-switcher dropdown">
    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-globe me-2"></i>
        @php
            $currentLocale = request()->cookie('locale', app()->getLocale());
        @endphp
        @if($currentLocale == 'hil')
            <span class="flag-icon flag-icon-ph me-1"></span><span class="btn-text">{{ __('common.hiligaynon') }}</span>
        @else
            <span class="flag-icon flag-icon-us me-1"></span><span class="btn-text">{{ __('common.english') }}</span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                <span class="flag-icon flag-icon-us me-2"></span>{{ __('common.english') }}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('language.switch', 'hil') }}">
                <span class="flag-icon flag-icon-ph me-2"></span>{{ __('common.hiligaynon') }}
            </a>
        </li>
    </ul>
</div>

<style>
.language-switcher .dropdown-menu {
    min-width: 150px;
}

.language-switcher .dropdown-item {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
}

.language-switcher .dropdown-item:hover {
    background-color: #f8f9fa;
}

.flag-icon {
    width: 16px;
    height: 12px;
    border-radius: 2px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .language-switcher {
        width: 100%;
    }

    .language-switcher .btn {
        font-size: 0.9rem;
        padding: 0.625rem 1rem;
        width: 100%;
        min-height: 44px; /* Touch-friendly */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .language-switcher .btn .btn-text {
        display: inline-block;
        margin-left: 0.5rem;
    }

    .language-switcher .btn i {
        margin-right: 0.5rem !important;
    }

    .language-switcher .dropdown-menu {
        width: 100%;
        min-width: 100%;
    }
}

@media (max-width: 576px) {
    .language-switcher .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }

    .language-switcher .btn .btn-text {
        font-size: 0.875rem;
    }
}
</style>