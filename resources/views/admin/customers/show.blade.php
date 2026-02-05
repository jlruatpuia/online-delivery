@extends('admin.layout')
@section('title', 'Delivery Detail')
@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="/admin/dashboard">
                                    <i class="fa fa-tachometer-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">Customer</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Customer Detail</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="row">
                <!-- 🏠 Address Form -->
                <div class="col-md-7">
                    <div class="mb-2">
                        <label>House No</label>
                        <input id="house_no" name="house_no"
                               class="form-control"
                               value="{{ old('house_no', $customer->new_address['house_no'] ?? '') }}">
                    </div>

                    <div class="mb-2">
                        <label>Area / Village</label>
                        <input id="area_village" name="area_village"
                               class="form-control"
                               value="{{ old('area_village', $customer->new_address['area_village'] ?? '') }}">
                    </div>

                    <div class="mb-2">
                        <label>Landmark</label>
                        <input id="landmark" name="landmark"
                               class="form-control"
                               value="{{ old('landmark', $customer->new_address['landmark'] ?? '') }}">
                    </div>

                    <div class="mb-2">
                        <label>Town / City</label>
                        <input id="town_city" name="town_city"
                               class="form-control"
                               value="{{ old('town_city', $customer->new_address['town_city'] ?? '') }}">
                    </div>

                    <div class="mb-2">
                        <label>State</label>
                        <select id="state" name="state" class="custom-select">
                            <option value="AP">Andhra Pradesh</option>
                            <option value="AR">Arunachal Pradesh</option>
                            <option value="AS">Assam</option>
                            <option value="BR">Bihar</option>
                            <option value="CT">Chhattisgarh</option>
                            <option value="GA">Gujarat</option>
                            <option value="HR">Haryana</option>
                            <option value="HP">Himachal Pradesh</option>
                            <option value="JK">Jammu and Kashmir</option>
                            <option value="GA">Goa</option>
                            <option value="JH">Jharkhand</option>
                            <option value="KA">Karnataka</option>
                            <option value="KL">Kerala</option>
                            <option value="MP">Madhya Pradesh</option>
                            <option value="MH">Maharashtra</option>
                            <option value="MN">Manipur</option>
                            <option value="ML">Meghalaya</option>
                            <option value="MZ" selected>Mizoram</option>
                            <option value="NL">Nagaland</option>
                            <option value="OR">Odisha</option>
                            <option value="PB">Punjab</option>
                            <option value="RJ">Rajasthan</option>
                            <option value="SK">Sikkim</option>
                            <option value="TN">Tamil Nadu</option>
                            <option value="TG">Telangana</option>
                            <option value="TR">Tripura</option>
                            <option value="UT">Uttarakhand</option>
                            <option value="UP">Uttar Pradesh</option>
                            <option value="WB">West Bengal</option>
                            <option value="AN">Andaman and Nicobar Islands</option>
                            <option value="CH">Chandigarh</option>
                            <option value="DN">Dadra and Nagar Haveli</option>
                            <option value="DD">Daman and Diu</option>
                            <option value="DL">Delhi</option>
                            <option value="LD">Lakshadweep</option>
                            <option value="PY">Puducherry</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>PIN</label>
                        <input id="pin" name="pin"
                               class="form-control"
                               value="{{ old('pin', $customer->new_address['pin'] ?? '') }}">
                    </div>

                    <div class="mb-2">
                        <label>Region</label>
                        <input id="region" name="region"
                               class="form-control"
                               value="India">
                    </div>

                    <button type="button"
                            id="btnGetLocation"
                            class="btn btn-outline-primary mt-2">
                        📍 Get Map Location
                    </button>

                    <!-- hidden lat/lng -->
                    <input type="text" id="lat" name="lat"
                           value="{{ $customer->map_location['lat'] ?? '' }}">
                    <input type="text" id="lng" name="lng"
                           value="{{ $customer->map_location['lng'] ?? '' }}">
                </div>

                <!-- 🗺️ Map Preview -->
                <div class="col-md-5">
                    <div id="mapPreview"
                         class="card"
                         style="{{ empty($customer->map_location) ? 'display:none' : '' }}">

                        <div class="card-body p-2">
                            <img id="staticMap"
                                 class="img-fluid rounded"
                                 alt="Map preview"
                                 src="{{ !empty($customer->map_location)
                        ? 'https://maps.googleapis.com/maps/api/staticmap?center='.
                          $customer->map_location['lat'].','.$customer->map_location['lng'].
                          '&zoom=16&size=500x350&markers=color:red|'.
                          $customer->map_location['lat'].','.$customer->map_location['lng'].
                          '&key='.config('services.google.maps_key')
                        : '' }}">

                            <div class="text-center mt-2">
                                <a id="openMap"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary"
                                   href="{{ !empty($customer->map_location)
                        ? 'https://www.google.com/maps?q='.
                          $customer->map_location['lat'].','.$customer->map_location['lng']
                        : '#' }}">
                                    Open in Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
{{--                    <div id="map"--}}
{{--                         style="width:100%;height:350px;display:none"--}}
{{--                         class="rounded border"></div>--}}
                </div>
            </div>

        </div>

    </div>

@endsection
@section('scripts')
    <script>
        document.getElementById('btnGetLocation')
            .addEventListener('click', async () => {
                //var lat, lng;
                const fields = [
                    'house_no','area_village','landmark',
                    'town_city','state','pin','region'
                ];

                const address = fields
                    .map(id => document.getElementById(id)?.value?.trim())
                    .filter(v => v)
                    .join(', ') + ', India';

                if (!address.trim()) {
                    alert('Please fill address fields first.');
                    return;
                }

                const res = await fetch('api/admin/geocode/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ address })
                });

                const data = await res.json();
                alert(data.message);
                if (!data.success) {
                    alert(data.message);
                    return;
                }

                lat.value = data.lat;
                lng.value = data.lng;
                alert(data.lat);
                updateStaticMap(data.lat, data.lng);
                {{--const apiKey = "{{ config('services.google.maps_key') }}";--}}

                {{--const res = await fetch(--}}
                {{--    `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`--}}
                {{--);--}}

                {{--const data = await res.json();--}}

                {{--if (data.status !== 'OK') {--}}
                {{--    alert('Location not found.');--}}
                {{--    return;--}}
                {{--}--}}

                {{--const loc = data.results[0].geometry.location;--}}

                {{--// set hidden fields--}}
                {{--lat.value = loc.lat;--}}
                {{--lng.value = loc.lng;--}}

                {{--// build static map--}}
                // const mapUrl =
                //     `https://maps.googleapis.com/maps/api/staticmap` +
                //     `?center=${loc.lat},${loc.lng}` +
                //     `&zoom=16&size=500x350` +
                //     `&markers=color:red|${loc.lat},${loc.lng}` +
                //     `&key=${apiKey}`;
                //
                // // update UI
                // document.getElementById('staticMap').src = mapUrl;
                // document.getElementById('openMap').href =
                //     `https://www.google.com/maps?q=${loc.lat},${loc.lng}`;
                //
                // document.getElementById('mapPreview').style.display = 'block';
            });
        function updateStaticMap(lat, lng) {

            if (!lat || !lng) return;

            const apiKey = "{{ config('services.google.maps_key') }}";

            // Update hidden fields
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            // Build static map URL
            const mapUrl =
                `https://maps.googleapis.com/maps/api/staticmap` +
                `?center=${lat},${lng}` +
                `&zoom=16` +
                `&size=500x350` +
                `&markers=color:red|${lat},${lng}` +
                `&key=${apiKey}`;

            // Update image
            const mapImg = document.getElementById('staticMap');
            mapImg.src = mapUrl;

            // Update Google Maps link
            const openMap = document.getElementById('openMap');
            openMap.href = `https://www.google.com/maps?q=${lat},${lng}`;

            // Show map preview container
            document.getElementById('mapPreview').style.display = 'block';
        }
    </script>
{{--<script--}}
{{--    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.static_key') }}">--}}
{{--</script>--}}
{{--<script>--}}
{{--    async function reverseGeocode(latVal, lngVal) {--}}

{{--        const res = await fetch('/admin/customers/reverse-geocode', {--}}
{{--            method: 'POST',--}}
{{--            headers: {--}}
{{--                'Content-Type': 'application/json',--}}
{{--                'X-CSRF-TOKEN': document--}}
{{--                    .querySelector('meta[name="csrf-token"]').content--}}
{{--            },--}}
{{--            body: JSON.stringify({--}}
{{--                lat: latVal,--}}
{{--                lng: lngVal--}}
{{--            })--}}
{{--        });--}}

{{--        const data = await res.json();--}}

{{--        if (!data.success) return;--}}

{{--        const addr = data.address;--}}

{{--        house_no.value = addr.house_no ?? '';--}}
{{--        area_village.value = addr.area_village ?? '';--}}
{{--        landmark.value = addr.landmark ?? '';--}}
{{--        town_city.value = addr.town_city ?? '';--}}
{{--        state.value = addr.state ?? '';--}}
{{--        pin.value = addr.pin ?? '';--}}
{{--        region.value = addr.region ?? '';--}}
{{--    }--}}
{{--</script>--}}

@endsection
