<?php 
$MasterKas = new App\Models\MasterKas;
$MasterAkun = new App\Models\MasterAkun;
$kodekas = $MasterKas::orderBy("nama", "ASC")->get();
$kodeakun = $MasterAkun::orderBy("nama", "ASC")->get();
?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown no-arrow w-100">
            <a class="bion-nav-link-kolom d-flex flex-column" href="/{{ $cabang }}/penjualan/add" style="width: 81px;">
                <i class="fas fa-cart-plus fa-fw text-dark"></i>
                <span class="text-dark">Transaksi</span>
            </a>
        </li>

        <li class="nav-item dropdown no-arrow w-100">
            <a class="bion-nav-link-kolom d-flex flex-column" href="#" id="kasmasukbtn" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="modal" data-target="#popup-kas-masuk" style="width: 81px;">
                <i class="fas fa-plus-square fa-fw text-dark"></i>
                <span class="text-dark">Kas Masuk</span>
            </a>
        </li>

        <li class="nav-item dropdown no-arrow w-100">
            <a class="bion-nav-link-kolom d-flex flex-column" href="#" id="kaskeluarbtn" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="modal" data-target="#popup-kas-keluar" style="width: 81px;">
                <i class="far fa-plus-square fa-fw text-dark"></i>
                <span class="text-dark">Kas Keluar</span>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter"></span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Pemberitahuan
                </h6>
                <!--<a class="dropdown-item d-flex align-items-center" href="#">-->
                <!--    <div class="mr-3">-->
                <!--        <div class="icon-circle bg-primary">-->
                <!--            <i class="fas fa-file-alt text-white"></i>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div>-->
                <!--        <div class="small text-gray-500">December 12, 2019</div>-->
                <!--        <span class="font-weight-bold">A new monthly report is ready to download!</span>-->
                <!--    </div>-->
                <!--</a>-->
                <!--<a class="dropdown-item d-flex align-items-center" href="#">-->
                <!--    <div class="mr-3">-->
                <!--        <div class="icon-circle bg-success">-->
                <!--            <i class="fas fa-donate text-white"></i>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div>-->
                <!--        <div class="small text-gray-500">December 7, 2019</div>-->
                <!--        $290.29 has been deposited into your account!-->
                <!--    </div>-->
                <!--</a>-->
                <!--<a class="dropdown-item d-flex align-items-center" href="#">-->
                <!--    <div class="mr-3">-->
                <!--        <div class="icon-circle bg-warning">-->
                <!--            <i class="fas fa-exclamation-triangle text-white"></i>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div>-->
                <!--        <div class="small text-gray-500">December 2, 2019</div>-->
                <!--        Spending Alert: We've noticed unusually high spending for your account.-->
                <!--    </div>-->
                <!--</a>-->
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama_belakang_karyawan !== NULL ? Auth::user()->nama_depan_karyawan . ' ' . Auth::user()->nama_belakang_karyawan : Auth::user()->nama_depan_karyawan }}</span>
                <img class="img-profile rounded-circle" src="{{ Auth::user()->photo_karyawan }}" style="background: #c3c3c3;">
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Keluar
                </a>
            </div>
        </li>

    </ul>
</nav>

<div class="modal fade" id="popup-kas-masuk" tabindex="-1" aria-labelledby="popup-kas-masuk" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-kas-masuk">Kas Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="kodekas">Kode Kas</label>
                    </div>
                    <select class="custom-select" id="kodekas" name="kodekas">
                        <option>Kode Kas</option>
                        @foreach($kodekas as $kas)
                        <option value="{{ $kas->nama }}" {{ ($kas->nama == "TUNAI" ? "selected" : "") }}>{{ $kas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="tglkas">Tanggal</label>
                    </div>
                    <div id="tglkas" class="form-control" style="display: flex;flex-direction: row;align-items: center; cursor: pointer;">
                        <i class="far fa-calendar-alt mr-2"></i>&nbsp;
                        <span id="tglinput" data-tgl-kas="{{ date("Y-m-d H:i:s") }}">{{ date("Y-m-d H:i:s") }}</span>
                        <i class="fa fa-caret-down ml-auto"></i>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="Tujuan Kas">Tujuan Kas</label>
                    </div>
                    <select class="custom-select" id="tujuankas" name="tujuankas">
                        <option selected>Pilih Tujuan Kas</option>
                        @foreach($kodeakun as $akun)
                        <option value="{{ $akun->kode }}">{{ $akun->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" autocomplete="off" class="form-control" aria-label="Jumlah" id="input-harga-live" name="tambahkas">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="keterangan-item">Keterangan</span>
                    </div>
                    <textarea class="form-control" aria-label="Keterangan" name="keteranganKas"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="tambah-kas-btn">Tambah</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-kas-popup">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popup-kas-keluar" tabindex="-1" aria-labelledby="popup-kas-keluar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-kas-keluar">Kas Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="kodekas">Kode Kas</label>
                    </div>
                    <select class="custom-select" id="kodekas" name="kodekas">
                        <option>Kode Kas</option>
                        @foreach($kodekas as $kas)
                        <option value="{{ $kas->nama }}" {{ ($kas->nama == "TUNAI" ? "selected" : "") }}>{{ $kas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="tglkas">Tanggal</label>
                    </div>
                    <div id="tglkas" class="form-control" style="display: flex;flex-direction: row;align-items: center; cursor: pointer;">
                        <i class="far fa-calendar-alt mr-2"></i>&nbsp;
                        <span id="tglinput" data-tgl-kas="{{ date("Y-m-d H:i:s") }}">{{ date("Y-m-d H:i:s") }}</span>
                        <i class="fa fa-caret-down ml-auto"></i>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="Tujuan Kas">Tujuan Kas</label>
                    </div>
                    <select class="custom-select" id="tujuankas" name="tujuankas">
                        <option selected>Pilih Tujuan Kas</option>
                        @foreach($kodeakun as $akun)
                        <option value="{{ $akun->kode }}">{{ $akun->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" autocomplete="off" class="form-control" aria-label="Jumlah" id="input-harga-live" name="tambahkas">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="keterangan-item">Keterangan</span>
                    </div>
                    <textarea class="form-control" aria-label="Keterangan" name="keteranganKas"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="tambah-kas-btn">Tambah</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-kas-popup">Batal</button>
            </div>
        </div>
    </div>
</div>