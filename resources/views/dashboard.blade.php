<!DOCTYPE html>
<html lang="fr">
    <!-- ================== SECTION HEAD ================== -->
    @include('layouts.head')

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>


	<!-- SECTION CONTENT -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- ================== SECTION HEADER ================== -->
        @include('layouts.header')

		<!-- ================== SECTION SIDEBAR ================== -->
        @include('layouts.sidebar')

		<!-- ================== SECTION BASE CONTENT ================== -->

            @yield('content')

		<!-- ================== SECTION CONFIG ================== -->
        @include('layouts.config')

		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>


        <!-- ================== SECTION SCRIPT JS ================== -->
        @include('layouts.scripts')
	</body>
</html>
