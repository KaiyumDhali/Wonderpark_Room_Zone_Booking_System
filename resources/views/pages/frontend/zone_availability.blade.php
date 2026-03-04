@extends('pages.frontend.layouts.app')
@section('content')

<style>
.map-wrapper{
    position: relative;
    max-width: 1200px;
    margin:auto;
}
.map-wrapper img{ 
    width:100%; 
    display:block; 
}

/* SVG overlay */
.map-overlay{ 
    position:absolute; 
    top:0; 
    left:0; 
    width:100%; 
    height:100%; 
    pointer-events: none;
}
.map-overlay svg{ 
    width:100%; 
    height:100%; 
}

/* Zones */
path[id^="zone-"]{ 
    fill: transparent; 
    transition:0.3s; 
    cursor:pointer; 
}

path.available{ 
    fill: rgba(0,255,0,0.35);
    pointer-events: all;
}

path.booked{ 
    fill: rgba(255,0,0,0.5); 
    pointer-events:none; 
    cursor:not-allowed;
}

/* ===== CUSTOM MODAL ===== */
.custom-modal{
    display:none;
    position:fixed;
    z-index:99999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
}

.custom-modal-content{
    background:#fff;
    padding:30px;
    width:100%;
    max-width:500px;
    border-radius:10px;
    position:relative;
    animation:fadeIn 0.3s ease;
}

.close-modal{
    position:absolute;
    top:10px;
    right:15px;
    font-size:25px;
    cursor:pointer;
}

@keyframes fadeIn{
    from{transform:scale(0.8);opacity:0;}
    to{transform:scale(1);opacity:1;}
}
</style>

<div class="container mt-5">
    <h2 class="text-center mb-5">Zone Availability</h2>

    <div class="map-wrapper">
        <img src="{{ asset('images/map/map.jpeg') }}" alt="Map">
        <div class="map-overlay">
            {!! file_get_contents(public_path('images/map/zones.svg')) !!}
        </div>
    </div>
</div>

<!-- ===== CUSTOM MODAL ===== -->
<div id="customModal" class="custom-modal">
  <div class="custom-modal-content">
    <span class="close-modal">&times;</span>
    
    <h4>Book Zone</h4>

    <form method="POST" action="{{ route('frontend.book.spot') }}">
        @csrf
        <input type="hidden" name="spot_id" id="spot_id">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mobile</label>
            <input type="text" name="customer_mobile" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Booking Date</label>
            <input type="date" name="booking_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Total Persons</label>
            <input type="number" name="total_persons" class="form-control" required>
        </div>

        <button type="submit" class="theme-btn btn-style-one w-100">Book Now</button>
    </form>
  </div>
</div>

@php
$zoneMapArray = [
    1 => 'zone-1',
    2 => 'zone-2',
    3 => 'zone-3',
    4 => 'zone-4',
    5 => 'zone-5',
    6 => 'zone-6'
];

$zoneStatusArray = $zones->mapWithKeys(function($zone) use ($availability) {
    return [$zone->id => $availability[$zone->id] ?? 'available'];
})->toArray();
@endphp

<script>
document.addEventListener("DOMContentLoaded", function() {

    var zoneMap = @json($zoneMapArray);
    var zoneStatus = @json($zoneStatusArray);

    var modalEl = document.getElementById('customModal');
    var closeBtn = document.querySelector('.close-modal');

    for (var dbId in zoneStatus){

        var svgId = zoneMap[dbId];
        if(!svgId) continue;

        var zone = document.getElementById(svgId);
        if(!zone) continue;

        zone.classList.add(zoneStatus[dbId]);

        if(zoneStatus[dbId] === 'available'){

            zone.addEventListener('click', (function(id){
                return function(){
                    document.getElementById('spot_id').value = id;
                    modalEl.style.display = "flex";
                }
            })(dbId));

        }
    }

    closeBtn.onclick = function(){
        modalEl.style.display = "none";
    }

    window.onclick = function(e){
        if(e.target == modalEl){
            modalEl.style.display = "none";
        }
    }

});
</script>

@endsection