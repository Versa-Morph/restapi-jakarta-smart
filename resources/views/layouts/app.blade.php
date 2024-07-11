<!DOCTYPE html>
<html dir="ltr" lang="id" class="no-outlines">
<head>
    @include('layouts.partials.head')
</head>
<body>

    <!-- Wrapper Start -->
    <div class="wrapper">
        @include('layouts.partials.navbar')

        @include('layouts.partials.sidebar')

        <!-- Main Container Start -->
        <main class="main--container">
            @yield('breadcumbs')

            @yield('content')

            {{-- @include('layouts.partials.footer') --}}
        </main>
        <!-- Main Container End -->
    </div>
    <!-- Wrapper End -->

    @include('layouts.partials.foot')

</body>
</html>
