<?php
/***********************************************************
maptracker.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 23, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/
?>

@extends('layouts.layout',['page_title' => "Map Tracker"])

@section('plugins')
    <script src="{{ url('js/libraries/shipment/maptracker.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Map Tracker</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
                <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                        <div class="col-md-4">
                <div class="form-group">
                    <label> Client </label>
                    <select class="bs-select form-control" name="fk_client_id" id="FK_CLIENT_ID" data-actions-box="true">
                            <option value=""> - Select One - </option>
                    </select>
                </div>
                                        </div>
                                        <div class="col-md-4">
                <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <div class="col-md-4">
                <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                                <div class="m-separator m-separator--dashed d-xl-none"></div>
                        </div>
                </div>
			<div id="MapTracker">
            <div id="map" style="height:600px;width:100%;"></div>
                <script>
                  // Note: This example requires that you consent to location sharing when
                  // prompted by your browser. If you see the error "The Geolocation service
                  // failed.", it means you probably did not give permission for the browser to
                  // locate you.
                  var map, infoWindow;
                  function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                      center: {lat: -34.397, lng: 150.644},
                      zoom: 6
                    });
                    infoWindow = new google.maps.InfoWindow;

                    // Try HTML5 geolocation.
                    if (navigator.geolocation) {
                      navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = {
                          lat: position.coords.latitude,
                          lng: position.coords.longitude
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Location found.');
                        infoWindow.open(map);
                        map.setCenter(pos);
                      }, function() {
                        handleLocationError(true, infoWindow, map.getCenter());
                      });
                    } else {
                      // Browser doesn't support Geolocation
                      handleLocationError(false, infoWindow, map.getCenter());
                    }
                  }

                  function addMarker(location) {
                      marker = new google.maps.Marker({
                          position: location,
                          map: map
                      });
                  }

                  // Testing the addMarker function
                  CentralPark = new google.maps.LatLng(33.888630, 35.495480);
                  addMarker(CentralPark);

                  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(browserHasGeolocation ?
                                          'Error: The Geolocation service failed.' :
                                          'Error: Your browser doesn\'t support geolocation.');
                    infoWindow.open(map);
                  }
                </script>
                <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaLB6HmQpYXe8ume11TvXtfQdiQljUkko&callback=initMap">
                </script>
			</div>
    </div>
</div>

@endsection