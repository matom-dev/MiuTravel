<section class="card mt-4 mx-3 agency-departures" id="departures">
    <div class="card-header"><h2 class="h5 mb-0">Ngày đi, ngày về và số chỗ</h2></div>
    <div class="card-body">
        <p>Mỗi đợt khởi hành có ngày đi, ngày về và số chỗ riêng. Mỗi hành khách chiếm một chỗ.</p>
        @foreach($tour->schedules as $schedule)
            <details class="border rounded p-3 mb-3"><summary>Sửa đợt {{ substr($schedule->ts_start_date,0,10) }} — Còn {{ $schedule->remaining_seats }}/{{ $schedule->ts_number_guests }} chỗ</summary>
                <div class="mt-3">@include('agency.schedule-form')</div>
            </details>
        @endforeach
        <h3 class="h6 font-weight-bold">Thêm đợt khởi hành</h3>
        @include('agency.schedule-form', ['schedule'=>new \App\Models\TourSchedule])
    </div>
</section>
<style>
.agency-departures .agency-fields {display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px}
.agency-departures label {display:block}
.agency-departures .form-control {margin-top:6px}
</style>
