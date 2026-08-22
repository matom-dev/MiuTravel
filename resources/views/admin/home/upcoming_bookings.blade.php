@extends('admin.layouts.main')
@section('title', 'Tour sắp khởi hành')
@section('style-css')
    <style>
        .booking-overview-table th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .booking-overview-table td {
            vertical-align: middle;
        }
        .booking-tour-name {
            color: #2563eb;
            font-weight: 700;
        }
        .booking-guest-count {
            color: #f15b2a;
            font-weight: 800;
            white-space: nowrap;
        }
    </style>
@stop
@section('content')
    <section class="content pt-4">
        <div class="container-fluid">
            @include('admin.home._booking_list_table', ['listTitle' => 'Danh sách tour sắp khởi hành'])
        </div>
    </section>
@stop
