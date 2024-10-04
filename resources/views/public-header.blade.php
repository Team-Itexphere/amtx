    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-0">
        <div class="row g-0 d-none d-lg-flex">
            <div class="col-lg-6 ps-5 text-start">
                <!--div class="h-100 d-inline-flex align-items-center text-white">
                    <span>Follow Us:</span>
                    <a class="btn btn-link text-light" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-link text-light" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-link text-light" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-link text-light" href=""><i class="fab fa-instagram"></i></a>
                </div-->
            </div>
            <div class="col-lg-6 text-end">
                <div class="h-100 topbar-right d-inline-flex align-items-center text-white py-2 px-5">
                    <span class="fs-5 fw-bold me-2"><i class="fa fa-phone-alt me-2"></i>Call Us:</span>
                    <span class="fs-5 fw-bold">+281 242 2687</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-0 pe-5">
        <a href="/" class="navbar-brand ps-5 me-0">
            <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" height="70" />
        </a>
        <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="/" class="nav-item nav-link {{ Route::is('site-url') || Route::is('home') ? 'active' : '' }}">Home</a>
                <a href="about-us" class="nav-item nav-link {{ Route::is('about-us') ? 'active' : '' }}">About Us</a>
                <a href="our-service" class="nav-item nav-link {{ Route::is('our-service') ? 'active' : '' }}">Service</a>
                <a href="contact-us" class="nav-item nav-link {{ Route::is('contact-us') ? 'active' : '' }}">Contact Us</a>
            </div>
            <a href="{{ auth()->user() ? 'dashboard' : 'login' }}" class="btn btn-primary px-3 d-none d-lg-block">{{ auth()->user() ? 'Dashboard' : 'Login' }}</a>
            @if (auth()->user())
                <a href="logout" class="btn btn-primary px-3 d-none d-lg-block ms-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endif
        </div>
    </nav>
    <!-- Navbar End -->