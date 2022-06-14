@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><span class="fa fa-file-excel fa-fw"></span>  Cetak Laporan Excel</h1>
    </div>
    <form id="submitform" action="{{route('lapExport')}}" method="POST">
    @csrf
        <div class="form-group row col-md-3 mb-3">
            <label for="name" class>Pilih Bulan</label>
            <select name="bulan" id="bulan" class="form-control">
                <option value="">Pilih Bulan Laporan</option>
                @foreach ($bulan as $bulanalp => $bulanno)
                <option value="{{$bulanalp}}">{{$bulanalp}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row col-md-3 mb-3">
            <label for="name" class>Pilih Tahun</label>
            <select name="tahun" id="tahun" class="form-control">
                <option value="">Pilih Tahun Laporan</option>
                @foreach ($tahun as $tahuna => $tahunb)
                <option value="{{$tahuna}}">{{$tahunb}}</option>
                @endforeach
            </select>
        </div>
        <button form="submitform" type="submit" class="btn btn-primary"> Cetak Laporan </button>
    </form>
</main>
@endsection