<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-5">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('classrooms.*')) active @endif"  aria-current="page" href="/classrooms">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  @if(request()->routeIs('classrooms.classworks.*')) active @endif"  href="">Classworks</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"  href="">Peoples</a>
                    </li>

                </ul>
                <x-user-notifications-menu count="5"/>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
</header>
