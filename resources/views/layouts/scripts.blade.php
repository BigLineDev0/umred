<!-- ================== BEGIN BASE JS ================== -->
	<script src="{{ asset('assets/js/app.min.js') }}"></script>
	<script src="{{ asset('assets/js/theme/default.min.js') }}"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="{{ asset('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.canvaswrapper.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.colorhelpers.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.saturated.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.browser.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.drawSeries.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.uiConstants.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.time.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.resize.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.pie.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.crosshair.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.categories.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.navigate.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.touchNavigate.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.hover.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.touch.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.selection.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.symbol.js') }}"></script>
	<script src="{{ asset('assets/plugins/flot/source/jquery.flot.legend.js') }}"></script>
	<script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/jvectormap-next/jquery-jvectormap.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js') }}"></script>
	<script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('assets/js/demo/dashboard.js') }}"></script>

    <!-- ================== DATATABLE JS ================== -->
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/demo/table-manage-default.demo.js') }}"></script>
    <!-- ================== END DATATABLE JS ================== -->

<!-- ================== LINK SWEET ALERT ================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
