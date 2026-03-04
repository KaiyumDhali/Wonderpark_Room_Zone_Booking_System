<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
          
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h3 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            New Booking</h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->



              <div class="calendar">
                <div class="calendar-header">
                  December 2024
                </div>
                <div class="calendar-body">
                  <!-- Weekdays Row -->
                  <div class="row text-center">
                    <div class="col border py-2 fw-bold">Sun</div>
                    <div class="col border py-2 fw-bold">Mon</div>
                    <div class="col border py-2 fw-bold">Tue</div>
                    <div class="col border py-2 fw-bold">Wed</div>
                    <div class="col border py-2 fw-bold">Thu</div>
                    <div class="col border py-2 fw-bold">Fri</div>
                    <div class="col border py-2 fw-bold">Sat</div>
                  </div>
                  
                  <!-- Calendar Days -->
                  <div class="row text-center">
                    <!-- Empty days for padding (start of month) -->
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"><span class="day-number">1</span></div>
                    <div class="col day"><span class="day-number">2</span></div>
                  </div>
                  <div class="row text-center">
                    <div class="col day"><span class="day-number">3</span></div>
                    <div class="col day"><span class="day-number">4</span></div>
                    <div class="col day"><span class="day-number">5</span></div>
                    <div class="col day"><span class="day-number">6</span></div>
                    <div class="col day"><span class="day-number">7</span></div>
                    <div class="col day"><span class="day-number">8</span></div>
                    <div class="col day"><span class="day-number">9</span></div>
                  </div>
                  <div class="row text-center">
                    <div class="col day"><span class="day-number">10</span></div>
                    <div class="col day"><span class="day-number">11</span></div>
                    <div class="col day"><span class="day-number">12</span></div>
                    <div class="col day"><span class="day-number">13</span></div>
                    <div class="col day"><span class="day-number">14</span></div>
                    <div class="col day"><span class="day-number">15</span></div>
                    <div class="col day"><span class="day-number">16</span></div>
                  </div>
                  <div class="row text-center">
                    <div class="col day"><span class="day-number">17</span></div>
                    <div class="col day"><span class="day-number">18</span></div>
                    <div class="col day"><span class="day-number">19</span></div>
                    <div class="col day"><span class="day-number">20</span></div>
                    <div class="col day"><span class="day-number">21</span></div>
                    <div class="col day"><span class="day-number">22</span></div>
                    <div class="col day"><span class="day-number">23</span></div>
                  </div>
                  <div class="row text-center">
                    <div class="col day"><span class="day-number">24</span></div>
                    <div class="col day"><span class="day-number">25</span></div>
                    <div class="col day"><span class="day-number">26</span></div>
                    <div class="col day"><span class="day-number">27</span></div>
                    <div class="col day"><span class="day-number">28</span></div>
                    <div class="col day"><span class="day-number">29</span></div>
                    <div class="col day"><span class="day-number">30</span></div>
                  </div>
                  <div class="row text-center">
                    <div class="col day"><span class="day-number">31</span></div>
                    <!-- Empty cells (end of month) -->
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                    <div class="col day"></div>
                  </div>
                </div>
              </div>


            <div class="row g-5 g-xl-8">

                @foreach ($room as $room_item)
                    <div class="col-xl-2">
                        <a href="{{ route('room.index') }}" class="card bg-primary card-xl-stretch mb-5 mb-xl-8"
                            style="background-image:url('assets/media/svg/shapes/widget-bg-1.png')">
                            <!--begin::Body-->
                            <div class="card-body">
                                <i class="fa-solid fa-home-lg fs-4x text-light"></i>
                                <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $room_item->room_number }}</div>
                                <div class="fw-semibold text-white">Room</div>
                            </div>
                            <!--end::Body-->
                        </a>
                    </div>
                @endforeach
            </div>


        </div>
        <!--end::Content wrapper-->

    </div>
</x-default-layout>
