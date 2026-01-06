/**
 * Harvest Calendar Widget JavaScript
 * Handles calendar widget functionality: month navigation, AJAX data fetching, filtering, responsive rendering
 */

(function() {
    'use strict';

    class HarvestCalendar {
        constructor(containerId, options = {}) {
            this.container = document.getElementById(containerId);
            this.currentMonth = options.currentMonth || new Date().getMonth() + 1;
            this.dataUrl = options.dataUrl || '/forums/harvest-calendar/data';
            this.onMonthChange = options.onMonthChange || null;

            if (!this.container) {
                console.error('Harvest calendar container not found:', containerId);
                return;
            }

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadMonthData(this.currentMonth);
        }

        setupEventListeners() {
            const prevBtn = this.container.querySelector('#prev-month');
            const nextBtn = this.container.querySelector('#next-month');

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    this.navigateMonth(-1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    this.navigateMonth(1);
                });
            }
        }

        navigateMonth(direction) {
            this.currentMonth += direction;

            if (this.currentMonth < 1) {
                this.currentMonth = 12;
            } else if (this.currentMonth > 12) {
                this.currentMonth = 1;
            }

            this.loadMonthData(this.currentMonth);
        }

        loadMonthData(month) {
            const contentContainer = this.container.querySelector('#harvest-calendar-content');
            if (!contentContainer) {
                console.error('Harvest calendar content container not found');
                return;
            }

            // Show loading state
            contentContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            fetch(`${this.dataUrl}?month=${month}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.renderCalendar(data, contentContainer);
                    this.updateMonthHeader(data.month_name);

                    if (this.onMonthChange) {
                        this.onMonthChange(data);
                    }
                })
                .catch(error => {
                    console.error('Error loading calendar data:', error);
                    contentContainer.innerHTML = '<div class="alert alert-danger">Error loading calendar data. Please try again.</div>';
                });
        }

        renderCalendar(data, container) {
            let html = '';

            if (data.products_by_category && Object.keys(data.products_by_category).length > 0) {
                for (const [category, products] of Object.entries(data.products_by_category)) {
                    html += `<div class="mb-3">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-seedling me-2"></i>${this.escapeHtml(category)}
                        </h6>
                        <div class="row g-2">`;

                    products.forEach(product => {
                        html += `<div class="col-12 col-md-6">
                            <div class="card border-success border-opacity-25">
                                <div class="card-body p-2">
                                    <a href="${this.escapeHtml(product.url)}" class="text-decoration-none text-dark">
                                        <strong>${this.escapeHtml(product.product_name)}</strong>
                                    </a>
                                    <div class="small text-muted">
                                        ${product.harvest_start_date && product.harvest_end_date ?
                                            `${this.escapeHtml(product.harvest_start_date)} - ${this.escapeHtml(product.harvest_end_date)}` : ''}
                                        ${product.harvest_season ?
                                            `<span class="badge bg-success ms-1">${this.escapeHtml(product.harvest_season)}</span>` : ''}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-user me-1"></i>${this.escapeHtml(product.user)}
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
                    <p class="text-muted mb-0">No harvest posts for this month</p>
                </div>`;
            }

            container.innerHTML = html;
        }

        updateMonthHeader(monthName) {
            const monthNameElement = this.container.querySelector('.harvest-calendar-widget h6 strong');
            if (monthNameElement) {
                monthNameElement.textContent = monthName;
            }
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        setMonth(month) {
            if (month >= 1 && month <= 12) {
                this.currentMonth = month;
                this.loadMonthData(this.currentMonth);
            }
        }

        getCurrentMonth() {
            return this.currentMonth;
        }
    }

    // Filter functionality for full calendar page
    class HarvestCalendarFilters {
        constructor(options = {}) {
            this.categoryFilter = document.getElementById(options.categoryFilterId || 'filter-category');
            this.seasonFilter = document.getElementById(options.seasonFilterId || 'filter-season');
            this.monthFilter = document.getElementById(options.monthFilterId || 'filter-month');
            this.yearView = document.getElementById(options.yearViewId || 'calendar-year-view');

            this.init();
        }

        init() {
            if (this.categoryFilter) {
                this.categoryFilter.addEventListener('change', () => this.applyFilters());
            }
            if (this.seasonFilter) {
                this.seasonFilter.addEventListener('change', () => this.applyFilters());
            }
            if (this.monthFilter) {
                this.monthFilter.addEventListener('change', () => {
                    this.handleMonthFilter();
                    this.applyFilters();
                });
            }
        }

        applyFilters() {
            if (!this.yearView) return;

            const category = this.categoryFilter ? this.categoryFilter.value : '';
            const season = this.seasonFilter ? this.seasonFilter.value : '';
            const month = this.monthFilter ? parseInt(this.monthFilter.value) : 0;

            const monthCards = this.yearView.querySelectorAll('.month-card');

            monthCards.forEach(card => {
                const cardMonth = parseInt(card.dataset.month);
                let show = true;

                if (month > 0 && cardMonth !== month) {
                    show = false;
                }

                // Additional filtering by category and season can be added here
                // For now, we'll just filter by month

                if (show) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        handleMonthFilter() {
            if (!this.monthFilter || !this.yearView) return;

            const month = parseInt(this.monthFilter.value);
            if (month > 0) {
                const selectedCard = this.yearView.querySelector(`[data-month="${month}"]`);
                if (selectedCard) {
                    selectedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    }

    // Export for global use
    window.HarvestCalendar = HarvestCalendar;
    window.HarvestCalendarFilters = HarvestCalendarFilters;

    // Auto-initialize if container exists
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize widget if present
        const widgetContainer = document.querySelector('.harvest-calendar-widget');
        if (widgetContainer && !widgetContainer.dataset.initialized) {
            const calendar = new HarvestCalendar('harvest-calendar-widget', {
                currentMonth: parseInt(widgetContainer.dataset.currentMonth) || new Date().getMonth() + 1,
                dataUrl: widgetContainer.dataset.dataUrl || '/forums/harvest-calendar/data'
            });
            widgetContainer.dataset.initialized = 'true';
        }

        // Initialize filters if present
        if (document.getElementById('filter-category') || document.getElementById('filter-season') || document.getElementById('filter-month')) {
            new HarvestCalendarFilters();
        }
    });
})();

