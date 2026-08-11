@extends('admin.layouts.main')
@section('title', 'Chỉnh sửa dịch vụ thuê xe')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="nav-icon fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('car.rental.index') }}">Thuê xe</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        @include('admin.car_rental.form')
    </section>
@stop
