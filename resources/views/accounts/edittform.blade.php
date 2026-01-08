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
            <h3 class="card-title">Edit Client</h3>
        </div>

        <div class="card-body">

            <form id="FORM_SAVE_ACCOUNT">

                {!! csrf_field() !!}
                <input type="hidden" name="ca_id" value="{{ $account_info->ca_id }}">
                <input type="hidden" name="fk_account_owner_id" value="{{ $account_info->fk_account_owner_id }}">

                {{-- SUCCESS / ERROR --}}
                <div class="alert alert-success d-none success-msg">Client Information updated successfully!</div>
                <div class="alert alert-danger d-none error-msg">Please check the form below.</div>

                {{-- BASIC INFO --}}
                <div class="row g-3">
                    <div class="section-title">Client Basic Information</div>

                    @if($crm_client_select_lead == 1)
                        <div class="col-md-4">
                            <label>Lead</label>
                            <select name="ca_lead_id" id="CA_LEAD_ID" class="form-control form-select">
                                <option value="">-- Select Lead --</option>
                                @foreach($lst_leads as $lead)
                                    <option value="{{ $lead->cl_id }}" {{ $account_info->ca_lead_id == $lead->cl_id ? 'selected' : '' }}>
                                        {{ $lead->cl_first_name }} {{ $lead->cl_last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label>Name *</label>
                        <input type="text" name="ca_account_name" class="form-control" required value="{{ $account_info->ca_account_name }}">
                    </div>

                    <div class="col-md-4">
                        <label>Client Code *</label>
                        <input type="text" name="ca_account_code" class="form-control" required value="{{ $account_info->ca_account_code }}">
                    </div>

                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="ca_account_email" class="form-control" value="{{ $account_info->ca_account_email }}">
                    </div>

                    <div class="col-md-4">
                        <label>Mobile *</label>
                        <input type="text" name="ca_account_mobile" class="form-control" required value="{{ $account_info->ca_account_mobile }}">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Contract Type </label>
                            <select name="ca_contract_type" id="CA_CONTRACT_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Contract Type">
                                <option value="0">-- Select Contract Type --</option>
                                @foreach( $lst_contract_types as $key => $type_info )
                                    <option {{ $account_info->ca_contract_type == $type_info->ct_id ? 'selected' : '' }} value="{{ $type_info->ct_id }}">{{ $type_info->ct_contract_type }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Nationality *</label>
                        <select name="ca_nationality_id" class="form-control" required>
                            <option value="">-- Select Nationality --</option>
                            @foreach($lst_nationalities as $nat)
                                <option value="{{ $nat->sn_id }}" {{ $account_info->ca_nationality_id == $nat->sn_id ? 'selected' : '' }}>
                                    {{ $nat->sn_nationality_fem_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Paper Type *</label>
                        <select name="ca_paper_type" class="form-control" required>
                            <option value="">-- Select Paper Type --</option>
                            @foreach($lst_paper_types as $type)
                                <option value="{{ $type->pt_id }}" {{ $account_info->ca_paper_type == $type->pt_id ? 'selected' : '' }}>
                                    {{ $type->pt_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>ID Number *</label>
                        <input type="text" name="ca_national_id" class="form-control" required value="{{ $account_info->ca_national_id }}">
                    </div>
                </div>

                <hr class="my-4">

                {{-- LOCATION & MAP --}}
                <div class="row g-3">
                    <div class="section-title">Location & Address</div>

                    <div class="col-md-12">
                        <label>Search Location (Google Autocomplete)</label>
                        <input id="GOOGLE_AUTOCOMPLETE" type="text" class="form-control" value="{{ $account_info->ca_location }}" placeholder="Type location..." />
                    </div>

                    <div class="col-md-12">
                        <button type="button" class="gps-btn" onclick="useMyGPS()">Use My GPS Location</button>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div id="map"></div>
                    </div>

                    <input type="hidden" id="LATITUDE" name="ca_account_lat" value="{{ $account_info->ca_account_lat }}">
                    <input type="hidden" id="LONGITUDE" name="ca_account_long" value="{{ $account_info->ca_account_long }}">
                </div>

                {{-- ADDRESS FIELDS --}}
                <div class="row g-3 mt-4">

                    <div class="col-md-4">
                        <label>Area</label>
                        <input type="text" class="form-control" id="CA_BILLING_AREA" name="ca_billing_area" value="{{ $account_info->ca_billing_area }}">
                    </div>

                    <div class="col-md-4">
                        <label>Region</label>
                        <input type="text" class="form-control" id="CA_BILLING_REGION" name="ca_billing_region" value="{{ $account_info->ca_billing_region }}">
                    </div>

                    <div class="col-md-4">
                        <label>City</label>
                        <input type="text" class="form-control" id="CA_BILLING_CITY" name="ca_billing_city" value="{{ $account_info->ca_billing_city }}">
                    </div>

                    <div class="col-md-4">
                        <label>Street</label>
                        <input type="text" class="form-control" id="CA_BILLING_STREET" name="ca_billing_street" value="{{ $account_info->ca_billing_street }}">
                    </div>

                    <div class="col-md-4">
                        <label>House</label>
                        <input type="text" class="form-control" id="CA_BILLING_HOUSE" name="ca_billing_house" value="{{ $account_info->ca_billing_house }}">
                    </div>

                    <div class="col-md-12">
                        <label>Full Address</label>
                        <textarea id="CA_BILLING_ADDRESS" class="form-control" name="ca_billing_address" style="height: 140px;">{{ $account_info->ca_billing_address }}</textarea>
                    </div>

                </div>

                <hr class="my-4">

                {{-- DESCRIPTION --}}
                <div class="row g-3">
                    <div class="section-title">Client Description</div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <textarea name="ca_account_description" id="CA_ACCOUNT_DESCRIPTION" class="form-control" style="height:200px;"></textarea>
                        </div>
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

    {{-- GOOGLE MAP SCRIPT --}}
    <script>

        let map, marker, autocomplete;

        function initMap() {

            const initialLat = {{ $account_info->ca_account_lat ?? 33.8938 }};
            const initialLng = {{ $account_info->ca_account_long ?? 35.5018 }};

            const defaultLoc = { lat: initialLat, lng: initialLng };

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLoc,
                zoom: 14,
            });

            marker = new google.maps.Marker({
                position: defaultLoc,
                map: map,
                draggable: true
            });

            marker.addListener('dragend', function () {
                const pos = marker.getPosition();
                updateLatLng(pos.lat(), pos.lng());
                reverseGeocode(pos);
            });

            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("GOOGLE_AUTOCOMPLETE"),
                {
                    fields: ["address_components", "geometry", "formatted_address"],
                    componentRestrictions: { country: "lb" }
                }
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
            const geocoder = new google.maps.Geocoder();

            geocoder.geocode({ location: latlng, region: "LB" }, (results, status) => {
                if (status === "OK" && results[0]) {
                    fillFields(results[0]);
                }
            });
        }

        function useMyGPS() {
            if (!navigator.geolocation) {
                alert("Invalid GPS");
                return;
            }

            navigator.geolocation.getCurrentPosition(position => {

                const loc = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                map.setCenter(loc);
                marker.setPosition(loc);

                updateLatLng(loc.lat, loc.lng);
                reverseGeocode(loc);

            }, () => {
                alert("Cannot Access with Location");
            });
        }

        window.onload = initMap;

    </script>

@endsection
