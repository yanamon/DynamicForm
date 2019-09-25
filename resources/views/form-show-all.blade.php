@extends('layouts.dynamic-form-layout')
@section('body')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="table-responsive" style="background:white;padding:15px 5px;">
                <table id="example" class="table table-bordered">
                    <thead class="thead-dark">
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>NIP</th>
                        <th>Alamat</th>
                        <th>Tanggal Lahir</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Wayan Made</td>
                            <td>19283336243</td>
                            <td>Jalan Danau Tondano No.58A</td>
                            <td>19-05-1970</td>
                            <td>Belum Disinkronisasi</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Nyoman Made</td>
                            <td>19285453281</td>
                            <td>Jalan Pulau Toba No.60</td>
                            <td>30-05-1963</td>
                            <td>Belum Disinkronisasi</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Alit Made</td>
                            <td>19283343281</td>
                            <td>Jalan Danau Buyan No.5</td>
                            <td>18-01-1980</td>
                            <td>Belum Disinkronisasi</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Gede Made</td>
                            <td>19283336281</td>
                            <td>Jalan Danau Tondano No.58A</td>
                            <td>25-12-1975</td>
                            <td>Belum Disinkronisasi</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Agung Made</td>
                            <td>19283336281</td>
                            <td>Jalan Gunung Agung No.3</td>
                            <td>19-05-1970</td>
                            <td>Belum Disinkronisasi</td>
                        </tr>
                    </tbody>
                </table>
                <center><button class="btn btn-info">Simpan Data ke Database</button></center>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> 
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );       
</script>
@endsection