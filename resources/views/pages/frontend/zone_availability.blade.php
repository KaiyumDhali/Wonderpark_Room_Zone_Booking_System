@extends('pages.frontend.layouts.app')
@section('content')

<style>
.map-wrapper{
    position: relative;
    max-width: 1600px;
    margin:auto;
    margin-top: -70px;
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
.zone-label{
    position:absolute;
    background:#ff0000;
    color:#fff;
    font-size:12px;
    padding:5px 8px;
    border-radius:4px;
    transform:translate(-50%,-120%);
    pointer-events:none;
    font-weight:600;
    white-space:nowrap;
}

/* flag arrow */
.zone-label::after{
    content:"";
    position:absolute;
    left:50%;
    bottom:-6px;
    transform:translateX(-50%);
    width:0;
    height:0;
    border-left:6px solid transparent;
    border-right:6px solid transparent;
    border-top:6px solid #ff0000;
}
@keyframes fadeIn{
    from{transform:scale(0.8);opacity:0;}
    to{transform:scale(1);opacity:1;}
}
.zone-container {
    background-image: url("{{ asset('images/map/istockphoto-155415994-612x612.jpg') }}");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 50px 0;
}
.date-range-box{
    margin-top:40px;   /* যত নিচে নামাতে চাও */
}
</style>

<div class=" mt-5 zone-container" >
@if(request('check_in') && request('check_out'))
<div class="text-center mb-4 date-range-box">
    <span class="badge bg-primary p-3" style="font-size:16px;">
        Showing Availability From 
        {{ \Carbon\Carbon::parse(request('check_in'))->format('d M Y') }} 
        To 
        {{ \Carbon\Carbon::parse(request('check_out'))->format('d M Y') }}
    </span>
</div>
@endif
    <div class="map-wrapper">
        <img src="{{ asset('images/map/map.png') }}" alt="Map">
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
            <label>Address</label>
            <input type="text" name="customer_address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Booking Date</label>
            <input type="date" name="booking_date" class="form-control" required>
        </div>

        <!-- <div class="mb-3">
            <label>Total Persons</label>
            <input type="number" name="total_persons" class="form-control" required>
        </div> -->

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
    var zoneStatus = @json($availability);

    var modalEl = document.getElementById('customModal');
    var closeBtn = document.querySelector('.close-modal');

   for (var dbId in zoneStatus){

    var svgId = zoneMap[dbId];
    if(!svgId) continue;

    var zone = document.getElementById(svgId);
    if(!zone) continue;

    zone.classList.add(zoneStatus[dbId].status);

    if(zoneStatus[dbId].status === 'booked'){

        var bbox = zone.getBBox();

        var label = document.createElement("div");
        label.className = "zone-label";

        label.innerHTML = "Booked<br>" + zoneStatus[dbId].date;

        label.style.left = (bbox.x + bbox.width/2) + "px";
        label.style.top = (bbox.y + bbox.height/2) + "px";

        document.querySelector(".map-wrapper").appendChild(label);
    }

    if(zoneStatus[dbId].status === 'available'){

        zone.style.pointerEvents = "all";

        zone.addEventListener("click", (function(id){

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