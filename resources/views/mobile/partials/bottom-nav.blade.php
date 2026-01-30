<div class="bottom-nav d-flex justify-content-around align-items-center">

    <a href="{{ route('mobile.dashboard') }}"
       class="text-center text-decoration-none text-dark">
        <i class="bi bi-house nav-icon"></i>
        <div class="small">Home</div>
    </a>

    <a href="{{ route('mobile.deliveries') }}"
       class="text-center text-decoration-none text-dark">
        <i class="bi bi-box-seam nav-icon"></i>
        <div class="small">Deliveries</div>
    </a>

{{--    <a href="{{ route('mobile.scan') }}"--}}
{{--    <a href="#"--}}
    <a href="{{ route('mobile.scan') }}"
       class="text-center text-decoration-none text-dark">
        <i class="bi bi-qr-code-scan nav-icon"></i>
        <div class="small">Scan</div>
    </a>

    <a href="{{ route('mobile.settlement') }}"
       class="text-center text-decoration-none text-dark">
        <i class="bi bi-cash-stack nav-icon"></i>
        <div class="small">Settlement</div>
    </a>

    <a href="{{ route('mobile.profile') }}"
       class="text-center text-decoration-none text-dark">
        <i class="bi bi-person nav-icon"></i>
        <div class="small">Profile</div>
    </a>

</div>
