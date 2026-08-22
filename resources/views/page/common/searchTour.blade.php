<style>
    .tour-search-submit {
        height: 50px !important;
        min-width: 132px;
        padding: 0 22px !important;
        border-radius: 12px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    @media (max-width: 767.98px) {
        .tour-search-submit {
            width: 100%;
        }
    }
</style>

<form action="{{ route('tour') }}" class="search-property-1">
    <input type="hidden" name="location_id" value="{{ Request::get('location_id') }}">
    <div class="row no-gutters">
        <div class="col-md d-flex">
            <div class="form-group p-4 border-0">
                <label for="#">Tour</label>
                <div class="form-field">
                    <div class="icon"><span class="fa fa-search"></span></div>
                    <input type="text" name="key_tour" value="{{ Request::get('key_tour') }}" class="form-control" placeholder="Tìm kiếm">
                </div>
            </div>
        </div>
        <div class="col-md d-flex">
            <div class="form-group p-4">
                <label for="#">Khoảng giá</label>
                <div class="form-field">
                    <div class="select-wrap">
                        <div class="icon"><span class="fa fa-chevron-down"></span></div>
                        <select name="price" id="" class="form-control">
                            <option value="">Chọn khoảng giá</option>
                            @foreach([
                                '0-1000000' => '0->1.000.000',
                                '1000000-2000000' => '1.000.000->2.000.000',
                                '2000000-3000000' => '2.000.000->3.000.000',
                                '3000000-4000000' => '3.000.000->4.000.000',
                                '4000000-5000000' => '4.000.000->5.000.000',
                                '5000000-6000000' => '5.000.000->6.000.000',
                                '6000000-7000000' => '6.000.000->7.000.000',
                                '7000000-8000000' => '7.000.000->8.000.000',
                                '8000000-9000000' => '8.000.000->9.000.000',
                                '9000000-10000000' => '9.000.000->10.000.000',
                                '10000000-11000000' => '10.000.000->11.000.000',
                                '11000000-12000000' => '11.000.000->12.000.000',
                                '12000000-13000000' => '12.000.000->13.000.000',
                                '13000000-14000000' => '13.000.000->14.000.000',
                                '14000000-15000000' => '14.000.000->15.000.000',
                                '15000000-100000000' => 'Trên 15.000.000',
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ Request::get('price') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md d-flex">
            <div class="form-group p-4">
                <label for="#">Thời lượng</label>
                <div class="form-field">
                    <div class="select-wrap">
                        <div class="icon"><span class="fa fa-chevron-down"></span></div>
                        <select name="duration" class="form-control">
                            <option value="">Bất kỳ</option>
                            @foreach(['1' => '1 ngày', '2-3' => '2-3 ngày', '4-5' => '4-5 ngày', '6+' => 'Từ 6 ngày'] as $value => $label)
                                <option value="{{ $value }}" {{ Request::get('duration') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md d-flex">
            <div class="form-group p-4">
                <label for="#">Loại tour</label>
                <div class="form-field">
                    <div class="select-wrap">
                        <div class="icon"><span class="fa fa-chevron-down"></span></div>
                        <select name="tour_type" class="form-control">
                            <option value="">Tất cả</option>
                            @foreach(($tourTypes ?? \App\Models\Tour::TOUR_TYPES) as $value => $label)
                                <option value="{{ $value }}" {{ Request::get('tour_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md d-flex">
            <div class="form-group d-flex w-100 border-0">
                <div class="form-field w-100 align-items-center d-flex">
                    <input type="submit" value="Tìm kiếm" class="form-control btn btn-primary tour-search-submit">
                </div>
            </div>
        </div>
    </div>
</form>
