@extends('layouts.shop')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/harvest-calendar.css') }}">
@endpush

@section('content')
<!-- Harvest Calendar Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="display-4 text-success fw-bold">{{ __('forums.harvest_calendar_title') }}</h1>
        <p class="lead">{{ __('forums.harvest_calendar_subtitle') }}</p>
    </div>
</div>

<!-- Harvest Calendar Content Section -->
<div class="container my-5">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="filter-category" class="form-label">{{ __('forums.filter_by_category') }}</label>
                    <select class="form-select" id="filter-category">
                        <option value="">{{ __('forums.all_categories') }}</option>
                        <option value="Vegetables">{{ __('forums.category_vegetables') }}</option>
                        <option value="Fruits">{{ __('forums.category_fruits') }}</option>
                        <option value="Grains">{{ __('forums.category_grains') }}</option>
                        <option value="Livestock">{{ __('forums.category_livestock') }}</option>
                        <option value="Herbs">{{ __('forums.category_herbs') }}</option>
                        <option value="Other">{{ __('forums.category_other') }}</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="filter-season" class="form-label">{{ __('forums.filter_by_season') }}</label>
                    <select class="form-select" id="filter-season">
                        <option value="">{{ __('forums.all_seasons') }}</option>
                        <option value="Spring">{{ __('forums.season_spring') }}</option>
                        <option value="Summer">{{ __('forums.season_summer') }}</option>
                        <option value="Fall">{{ __('forums.season_fall') }}</option>
                        <option value="Winter">{{ __('forums.season_winter') }}</option>
                        <option value="Dry Season">{{ __('forums.season_dry') }}</option>
                        <option value="Wet Season">{{ __('forums.season_wet') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter-month" class="form-label">{{ __('forums.month') }}</label>
                    <select class="form-select" id="filter-month">
                        <option value="0">{{ __('forums.all_seasons') }}</option>
                        <option value="1" {{ $currentMonth == 1 ? 'selected' : '' }}>{{ __('forums.month_january') }}</option>
                        <option value="2" {{ $currentMonth == 2 ? 'selected' : '' }}>{{ __('forums.month_february') }}</option>
                        <option value="3" {{ $currentMonth == 3 ? 'selected' : '' }}>{{ __('forums.month_march') }}</option>
                        <option value="4" {{ $currentMonth == 4 ? 'selected' : '' }}>{{ __('forums.month_april') }}</option>
                        <option value="5" {{ $currentMonth == 5 ? 'selected' : '' }}>{{ __('forums.month_may') }}</option>
                        <option value="6" {{ $currentMonth == 6 ? 'selected' : '' }}>{{ __('forums.month_june') }}</option>
                        <option value="7" {{ $currentMonth == 7 ? 'selected' : '' }}>{{ __('forums.month_july') }}</option>
                        <option value="8" {{ $currentMonth == 8 ? 'selected' : '' }}>{{ __('forums.month_august') }}</option>
                        <option value="9" {{ $currentMonth == 9 ? 'selected' : '' }}>{{ __('forums.month_september') }}</option>
                        <option value="10" {{ $currentMonth == 10 ? 'selected' : '' }}>{{ __('forums.month_october') }}</option>
                        <option value="11" {{ $currentMonth == 11 ? 'selected' : '' }}>{{ __('forums.month_november') }}</option>
                        <option value="12" {{ $currentMonth == 12 ? 'selected' : '' }}>{{ __('forums.month_december') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Year View - All 12 Months -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-success mb-3">{{ __('forums.harvest_calendar') }} - {{ date('Y') }}</h3>
        </div>
    </div>

    <div class="row g-4" id="calendar-year-view">
        @for($month = 1; $month <= 12; $month++)
            @php
                $monthData = $allMonthsData[$month] ?? null;
            @endphp
            <div class="col-md-6 col-lg-4 col-xl-3 month-card" data-month="{{ $month }}">
                <div class="card border-success h-100 {{ $month == $currentMonth ? 'border-3' : '' }}">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            @if($month == 1) {{ __('forums.month_january') }}
                            @elseif($month == 2) {{ __('forums.month_february') }}
                            @elseif($month == 3) {{ __('forums.month_march') }}
                            @elseif($month == 4) {{ __('forums.month_april') }}
                            @elseif($month == 5) {{ __('forums.month_may') }}
                            @elseif($month == 6) {{ __('forums.month_june') }}
                            @elseif($month == 7) {{ __('forums.month_july') }}
                            @elseif($month == 8) {{ __('forums.month_august') }}
                            @elseif($month == 9) {{ __('forums.month_september') }}
                            @elseif($month == 10) {{ __('forums.month_october') }}
                            @elseif($month == 11) {{ __('forums.month_november') }}
                            @else {{ __('forums.month_december') }}
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($monthData && isset($monthData['products_by_category']) && count($monthData['products_by_category']) > 0)
                            @php
                                $totalProducts = 0;
                                foreach($monthData['products_by_category'] as $products) {
                                    $totalProducts += count($products);
                                }
                            @endphp
                            <div class="mb-2">
                                <strong class="text-success">{{ $totalProducts }} {{ __('forums.products_available') }}</strong>
                            </div>
                            <div class="small">
                                @foreach($monthData['products_by_category'] as $category => $products)
                                    <div class="mb-1">
                                        <span class="badge bg-success bg-opacity-75">{{ $category }}</span>
                                        <span class="text-muted">({{ count($products) }})</span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('forums.harvest-calendar.month', $month) }}" class="btn btn-sm btn-outline-success mt-2 w-100">
                                {{ __('forums.view_product_posts') }}
                            </a>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0 small">{{ __('forums.no_harvest_posts') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Current Month Detail View -->
    @if(isset($harvestCalendar) && isset($harvestCalendar['products_by_category']) && count($harvestCalendar['products_by_category']) > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-success mb-3">{{ __('forums.available_this_month') }}: {{ $harvestCalendar['month_name'] }}</h3>
            </div>
        </div>

        <div class="row">
            @foreach($harvestCalendar['products_by_category'] as $category => $products)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-seedling me-2"></i>{{ $category }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($products as $product)
                                <div class="mb-3 pb-3 border-bottom">
                                    <a href="{{ $product['url'] }}" class="text-decoration-none">
                                        <h6 class="text-dark mb-1">{{ $product['product_name'] }}</h6>
                                    </a>
                                    <div class="small text-muted">
                                        @if($product['harvest_start_date'] && $product['harvest_end_date'])
                                            <i class="fas fa-calendar me-1"></i>{{ $product['harvest_start_date'] }} - {{ $product['harvest_end_date'] }}
                                        @endif
                                        @if($product['harvest_season'])
                                            <span class="badge bg-success ms-2">{{ $product['harvest_season'] }}</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-user me-1"></i>{{ $product['user'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.month-card .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.month-card .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.month-card .card.border-3 {
    border-width: 3px !important;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterCategory = document.getElementById('filter-category');
    const filterSeason = document.getElementById('filter-season');
    const filterMonth = document.getElementById('filter-month');
    const yearView = document.getElementById('calendar-year-view');

    function applyFilters() {
        const category = filterCategory.value;
        const season = filterSeason.value;
        const month = parseInt(filterMonth.value);

        const monthCards = yearView.querySelectorAll('.month-card');

        monthCards.forEach(card => {
            const cardMonth = parseInt(card.dataset.month);
            let show = true;

            if (month > 0 && cardMonth !== month) {
                show = false;
            }

            if (show) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    filterCategory.addEventListener('change', applyFilters);
    filterSeason.addEventListener('change', applyFilters);
    filterMonth.addEventListener('change', function() {
        if (this.value > 0) {
            // Scroll to selected month
            const selectedCard = yearView.querySelector(`[data-month="${this.value}"]`);
            if (selectedCard) {
                selectedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        applyFilters();
    });
});
</script>
@endpush
@endsection

