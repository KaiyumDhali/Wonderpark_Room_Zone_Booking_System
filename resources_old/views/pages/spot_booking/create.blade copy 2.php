<x-default-layout>

    {{-- Alerts --}}
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3>Create Spot Booking</h3>
                <span class="text-muted fs-7">Add new spot package booking</span>
            </div>
            <a href="{{ route('spot-bookings.index') }}" class="btn btn-sm btn-light">
                Back
            </a>
        </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="row g-6 mb-8">

                {{-- Calendar --}}
                <div class="col-md-7">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h4 class="card-title">Select Booking Date</h4>
                        </div>
                        <div class="card-body">
                            <div id="spot_booking_calendar"></div>
                        </div>
                    </div>
                </div>

                {{-- Spot Boxes --}}
                <div class="col-md-5">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h4 class="card-title">
                                Available Spots
                                <span id="selected_date_text" class="text-muted fs-7"></span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="spot_boxes">
                                <div class="text-muted fs-7">
                                    Select a date from calendar
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Hidden Booking Date --}}
            <input type="hidden" name="booking_date" id="booking_date">

            {{-- Selected Spots Grid --}}
            <div class="card card-flush mt-8">
                <div class="card-header">
                    <h4 class="card-title">Selected Spots</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4" id="selected_spots_grid">
                        <div class="text-muted fs-7">No spot selected</div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="selected_spots" id="selected_spots">
            

        </div>
    </div>

    {{-- Styles --}}
    <style>
        .spot-card {
            cursor: pointer;
            transition: .2s;
        }
        .spot-card:hover {
            background: #f1faff;
            transform: scale(1.03);
        }
        .spot-card.active {
            border: 2px solid #0d6efd !important;
        }
    </style>

    {{-- Data --}}
    <script>
        const spots = @json($spots);
        let selectedSpots = [];
    </script>

    {{-- FullCalendar CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const calendarEl = document.getElementById('spot_booking_calendar');
            const spotBox = document.getElementById('spot_boxes');
            const bookingDate = document.getElementById('booking_date');
            const dateText = document.getElementById('selected_date_text');
            const grid  = document.getElementById('selected_spots_grid');
            const input = document.getElementById('selected_spots');

            // Initialize FullCalendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 550,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                dateClick(info) {
                    bookingDate.value = info.dateStr;
                    dateText.textContent = `(${info.dateStr})`;
                    renderSpots();
                }
            });

            calendar.render();

            // Render spot cards
            function renderSpots() {
                spotBox.innerHTML = '';
                spots.forEach(spot => {
                    spotBox.insertAdjacentHTML('beforeend', `
                        <div class="col-6">
                            <div class="card spot-card"
                                 data-id="${spot.id}"
                                 data-title="${spot.title}"
                                 data-price="${spot.price}">
                                <div class="card-body p-4 text-center">
                                    <i class="bi bi-geo-alt fs-2 text-primary"></i>
                                    <div class="fw-bold mt-2">${spot.title}</div>
                                    <div class="text-muted fs-7">৳ ${spot.price}</div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }

            // Spot click → add to selected grid
            document.addEventListener('click', function(e) {
                const card = e.target.closest('.spot-card');
                if (!card) return;

                const spot = {
                    id: card.dataset.id,
                    title: card.dataset.title,
                    price: card.dataset.price
                };

                // Prevent duplicate
                if (selectedSpots.find(s => s.id == spot.id)) {
                    alert('Spot already added');
                    return;
                }

                selectedSpots.push(spot);
                renderGrid();
            });

            // Render selected grid
            function renderGrid() {
                grid.innerHTML = '';
                input.value = JSON.stringify(selectedSpots);

                if(selectedSpots.length === 0){
                    grid.innerHTML = `<div class="text-muted fs-7">No spot selected</div>`;
                    return;
                }

                selectedSpots.forEach((spot, index) => {
                    grid.insertAdjacentHTML('beforeend', `
                        <div class="col-md-4">
                            <div class="card border border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">${spot.title}</div>
                                            <div class="text-muted fs-7">৳ ${spot.price}</div>
                                        </div>
                                        <button type="button"
                                                class="btn btn-sm btn-light-danger"
                                                onclick="removeSpot(${index})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }

            // Remove spot from selected grid
            window.removeSpot = function(index){
                selectedSpots.splice(index, 1);
                renderGrid();
            }

        });
    </script>

</x-default-layout>
