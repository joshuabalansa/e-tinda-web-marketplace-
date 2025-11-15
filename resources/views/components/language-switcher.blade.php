<div class="language-switcher dropdown">
    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-globe me-2"></i>
        @php
            $currentLocale = request()->cookie('locale', app()->getLocale());
        @endphp
        @if($currentLocale == 'hil')
            <span class="flag-icon flag-icon-ph me-1"></span><span class="btn-text">Hiligaynon</span>
        @else
            <span class="flag-icon flag-icon-us me-1"></span><span class="btn-text">English</span>
        @endif
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                <span class="flag-icon flag-icon-us me-2"></span>English
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('language.switch', 'hil') }}">
                <span class="flag-icon flag-icon-ph me-2"></span>Hiligaynon
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
    .language-switcher .btn {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    .language-switcher .btn .btn-text {
        display: none;
    }
    
    .language-switcher .btn i {
        margin-right: 0 !important;
    }
}
</style>