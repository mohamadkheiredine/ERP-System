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
<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Map Tracker
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
						<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
							<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
								<i class="la la-ellipsis-h m--font-brand"></i>
							</a>
							<div class="m-dropdown__wrapper">
								<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
								<div class="m-dropdown__inner">
									<div class="m-dropdown__body">
										<div class="m-dropdown__content">
											<ul class="m-nav">
												<li class="m-nav__section m-nav__section--first">
													<span class="m-nav__section-text">
														Quick Actions
													</span>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-share"></i>
														<span class="m-nav__link-text">
															Print
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-chat-1"></i>
														<span class="m-nav__link-text">
															Export As CSV
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-multimedia-2"></i>
														<span class="m-nav__link-text">
															Import
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-multimedia-2"></i>
														<span class="m-nav__link-text">
															Download Import Template
														</span>
													</a>
												</li>

											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="m-portlet__body">
			<!--begin: Search Form -->
			<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
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
			</div>
			<!--end: Search Form -->
              <!--begin: Datatable -->
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
			<!--end: Datatable -->
		</div>
	</div>
@endsection