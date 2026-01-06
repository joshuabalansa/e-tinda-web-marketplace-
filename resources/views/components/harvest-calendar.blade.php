@props(['harvestCalendar', 'currentMonth'])

@push('styles')
<link rel="stylesheet" href="{{ asset('css/harvest-calendar.css') }}">
@endpush

<div class="harvest-calendar-widget" id="harvest-calendar-widget" data-current-month="{{ $currentMonth }}" data-data-url="{{ route('forums.harvest-calendar.data') }}">
    <div class="card border-success mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>{{ __('forums.harvest_calendar') }}
            </h5>
            <a href="{{ route('forums.harvest-calendar') }}" class="btn btn-sm btn-light text-success">
                {{ __('forums.view_full_calendar') }}
            </a>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __('forums.available_this_month') }}: <strong>{{ $harvestCalendar['month_name'] ?? date('F') }}</strong></h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-success" id="prev-month" data-month="{{ $currentMonth - 1 }}">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" id="next-month" data-month="{{ $currentMonth + 1 }}">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="harvest-calendar-content">
                @if(isset($harvestCalendar['products_by_category']) && count($harvestCalendar['products_by_category']) > 0)
                    @foreach($harvestCalendar['products_by_category'] as $category => $products)
                        <div class="mb-3">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-seedling me-2"></i>{{ $category }}
                            </h6>
                            <div class="row g-2">
                                @foreach($products as $product)
                                    <div class="col-12 col-md-6">
                                        <div class="card border-success border-opacity-25">
                                            <div class="card-body p-2">
                                                <a href="{{ $product['url'] }}" class="text-decoration-none text-dark">
                                                    <strong>{{ $product['product_name'] }}</strong>
                                                </a>
                                                <div class="small text-muted">
                                                    @if($product['harvest_start_date'] && $product['harvest_end_date'])
                                                        {{ $product['harvest_start_date'] }} - {{ $product['harvest_end_date'] }}
                                                    @endif
                                                    @if($product['harvest_season'])
                                                        <span class="badge bg-success ms-1">{{ $product['harvest_season'] }}</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $product['user'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('forums.no_harvest_posts') }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('forums.harvest-calendar') }}" class="btn btn-sm btn-outline-success">
                    {{ __('forums.view_full_calendar') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.harvest-calendar-widget .card {
    transition: box-shadow 0.3s ease;
}

.harvest-calendar-widget .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.harvest-calendar-widget .card-body .card {
    transition: transform 0.2s ease;
}

.harvest-calendar-widget .card-body .card:hover {
    transform: translateY(-2px);
}

.harvest-calendar-widget .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>

@push('scripts')
<script src="{{ asset('js/harvest-calendar.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const prevBtn = document.getElementById('prev-month');
    const nextBtn = document.getElementById('next-month');
    const calendarContent = document.getElementById('harvest-calendar-content');
    let currentMonth = {{ $currentMonth }};

    function updateCalendar(month) {
        if (month < 1) month = 12;
        if (month > 12) month = 1;
        currentMonth = month;

        fetch(`{{ route('forums.harvest-calendar.data') }}?month=${month}`)
            .then(response => response.json())
            .then(data => {
                let html = '';

                if (data.products_by_category && Object.keys(data.products_by_category).length > 0) {
                    for (const [category, products] of Object.entries(data.products_by_category)) {
                        html += `<div class="mb-3">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-seedling me-2"></i>${category}
                            </h6>
                            <div class="row g-2">`;

                        products.forEach(product => {
                            html += `<div class="col-12 col-md-6">
                                <div class="card border-success border-opacity-25">
                                    <div class="card-body p-2">
                                        <a href="${product.url}" class="text-decoration-none text-dark">
                                            <strong>${product.product_name}</strong>
                                        </a>
                                        <div class="small text-muted">
                                            ${product.harvest_start_date && product.harvest_end_date ?
                                                `${product.harvest_start_date} - ${product.harvest_end_date}` : ''}
                                            ${product.harvest_season ?
                                                `<span class="badge bg-success ms-1">${product.harvest_season}</span>` : ''}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fas fa-user me-1"></i>${product.user}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });

                        html += `</div></div>`;
                    }
                } else {
                    html = `<div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('forums.no_harvest_posts') }}</p>
                    </div>`;
                }

                calendarContent.innerHTML = html;

                // Update month name in header
                const monthNameElement = document.querySelector('.harvest-calendar-widget h6 strong');
                if (monthNameElement) {
                    monthNameElement.textContent = data.month_name;
                }
            })
            .catch(error => {
                console.error('Error loading calendar data:', error);
            });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            updateCalendar(currentMonth - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            updateCalendar(currentMonth + 1);
        });
    }
});
</script>
@endpush

