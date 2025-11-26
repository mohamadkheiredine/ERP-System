@extends('layouts.layout',['page_title' => "Clients Management"])

@section('themes')
    <style>
        #map {
            height: 350px;
            width: 100%;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
        }

        .section-title {
            font-weight: 600;
            font-size: 18px;
            padding: 12px;
            background: #0099cc;
            color: #fff;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .gps-btn {
            background: #28a745;
            color: #fff;
            font-size: 14px;
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            margin-top: 10px;
        }
    </style>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/clients.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/crm/saveclients.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUZSBHAoa4zU-ZBEtHTY4wSjEGlesvMu8&libraries=places&language=ar&region={{ session('country_code') }}"></script>
@endsection

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add Client</h3>
        </div>

        <div class="card-body">

            <form id="FORM_SAVE_ACCOUNT">

                {!! csrf_field() !!}
                <input type="hidden" name="fk_account_owner_id" value="{{ session('user_id') }}" />

                {{-- SUCCESS / ERROR --}}
                <div class="alert alert-success d-none success-msg">Client Information saved successfully!</div>
                <div class="alert alert-danger d-none error-msg">Please check the errors below.</div>

                {{-- BASIC INFO --}}
                <div class="row g-3">
                    <div class="section-title">Client Basic Information</div>

                    <div class="col-md-4">
                        <label>Name *</label>
                        <input type="text" name="ca_account_name" class="form-control" required />
                    </div>

                    <div class="col-md-4">
                        <label>Client Code *</label>
                        <input type="text" name="ca_account_code" class="form-control" value="{{ $client_code }}" required />
                    </div>

                    <div class="col-md-4">
                        <label>Email *</label>
                        <input type="email" name="ca_account_email" class="form-control" required />
                    </div>

                    <div class="col-md-4">
                        <label>Mobile *</label>
                        <input type="text" name="ca_account_mobile" class="form-control" required />
                    </div>

                    <div class="col-md-4">
                        <label>Nationality *</label>
                        <select name="ca_nationality_id" class="form-control" required>
                            <option value="">-- Select Nationality --</option>
                            @foreach($lst_nationalities as $n)
                                <option value="{{ $n->sn_id }}">{{ $n->sn_nationality_fem_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Paper Type *</label>
                        <select name="ca_paper_type" class="form-control" required>
                            <option value="">-- Select Paper Type --</option>
                            @foreach($lst_paper_types as $p)
                                <option value="{{ $p->pt_id }}">{{ $p->pt_description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>ID Number *</label>
                        <input type="text" name="ca_national_id" class="form-control" required />
                    </div>
                </div>

                <hr class="my-4">

                {{-- LOCATION SEARCH + MAP --}}
                <div class="row g-3">
                    <div class="section-title">Location & Address</div>

                    <div class="col-md-12">
                        <label>Search Location (Google Autocomplete)</label>
                        <input id="GOOGLE_AUTOCOMPLETE" name="ca_location" type="text" class="form-control" placeholder="Type location..." />
                    </div>

                    <div class="col-md-12">
                        <button type="button" class="gps-btn" onclick="useMyGPS()">Use My GPS Location</button>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div id="map"></div>
                    </div>

                    <input type="hidden" id="LATITUDE" name="ca_account_lat">
                    <input type="hidden" id="LONGITUDE" name="ca_account_long">
                </div>

                <div class="row g-3 mt-4">

                    <div class="col-md-4">
                        <label>Area</label>
                        <input type="text" class="form-control" id="CA_BILLING_AREA" name="ca_billing_area">
                    </div>

                    <div class="col-md-4">
                        <label>Region</label>
                        <input type="text" class="form-control" id="CA_BILLING_REGION" name="ca_billing_region">
                    </div>

                    <div class="col-md-4">
                        <label>City</label>
                        <input type="text" class="form-control" id="CA_BILLING_CITY" name="ca_billing_city">
                    </div>

                    <div class="col-md-4">
                        <label>Street</label>
                        <input type="text" class="form-control" id="CA_BILLING_STREET" name="ca_billing_street">
                    </div>

                    <div class="col-md-4">
                        <label>House</label>
                        <input type="text" class="form-control" name="ca_billing_house">
                    </div>

                    <div class="col-md-12">
                        <label>Full Address</label>
                        <textarea class="form-control" id="CA_BILLING_ADDRESS" name="ca_billing_address" style="height: 120px;"></textarea>
                    </div>
                </div>

                <hr class="my-4">

                {{-- DESCRIPTION --}}
                <div class="row g-3">
                    <div class="section-title">Client Description</div>

                    <div class="col-md-12">
                        <textarea name="ca_account_description" id="CA_ACCOUNT_DESCRIPTION" class="form-control" style="height:200px;"></textarea>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <button type="submit" name="btn_save_account" id="BTN_SAVE_ACCOUNT"  class="btn btn-info">Save</button>
                        <a href="{{ url()->previous() }}" class="btn btn-light">Back</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

    {{-- GOOGLE MAPS SCRIPT --}}
    <script>
        let map, marker, autocomplete;

        function initMap() {
            const defaultLoc = { lat: 33.8938, lng: 35.5018 }; // Beirut

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLoc,
                zoom: 12,
            });

            marker = new google.maps.Marker({
                position: defaultLoc,
                map: map,
                draggable: true
            });

            marker.addListener("dragend", () => {
                const pos = marker.getPosition();
                updateLatLng(pos.lat(), pos.lng());
                reverseGeocode(pos);
            });

            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("GOOGLE_AUTOCOMPLETE"),
                { fields: ["address_components", "geometry", "formatted_address"] }
            );

            autocomplete.addListener("place_changed", onPlaceChanged);
        }

        function onPlaceChanged() {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            const loc = place.geometry.location;

            map.setCenter(loc);
            map.setZoom(16);
            marker.setPosition(loc);

            updateLatLng(loc.lat(), loc.lng());
            fillFields(place);
        }

        function updateLatLng(lat, lng) {
            document.getElementById("LATITUDE").value = lat;
            document.getElementById("LONGITUDE").value = lng;
        }

        function fillFields(place) {
            let region="", area="", city="", street="";

            place.address_components.forEach(c => {
                if (c.types.includes("administrative_area_level_1")) region = c.long_name;
                if (c.types.includes("locality")) area = c.long_name;
                if (c.types.includes("administrative_area_level_2")) city = c.long_name;
                if (c.types.includes("route")) street = c.long_name;
            });

            document.getElementById("CA_BILLING_REGION").value = region;
            document.getElementById("CA_BILLING_AREA").value = area;
            document.getElementById("CA_BILLING_CITY").value = city;
            document.getElementById("CA_BILLING_STREET").value = street;
            document.getElementById("CA_BILLING_ADDRESS").value = place.formatted_address;
        }

        function reverseGeocode(latlng) {
            new google.maps.Geocoder().geocode({ location: latlng }, (res, status) => {
                if (status === "OK" && res[0]) fillFields(res[0]);
            });
        }

        function useMyGPS() {
            if (!navigator.geolocation) {
                alert("GPS is not supported.");
                return;
            }

            navigator.geolocation.getCurrentPosition(pos => {
                const loc = { lat: pos.coords.latitude, lng: pos.coords.longitude };

                map.setCenter(loc);
                marker.setPosition(loc);
                updateLatLng(loc.lat, loc.lng);
                reverseGeocode(loc);

            }, err => {
                alert("Unable to access GPS.");
            });
        }

        window.onload = initMap;
    </script>

@endsection
