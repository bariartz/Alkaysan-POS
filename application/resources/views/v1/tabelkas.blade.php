<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
$DB = new Illuminate\Support\Facades\DB;
$MasterKas = new App\Models\MasterKas;
$MasterAkun = new App\Models\MasterAkun;
$kodekas = $MasterKas::orderBy("nama", "ASC")->get();
$kodeakun = $MasterAkun::orderBy("nama", "ASC")->get();
?>
@if($agent->isDesktop())
<table id="penjualanTable" data-page-number="1" class="table text-center table-bordered table-hover table-responsive-sm" style="width:100%" data-for="kas">
    <thead class="thead-light">
        <tr>
            <th class="align-middle"><input type="checkbox" id="checkTabel" aria-label="Tandai transaksi"></th>
            <th class="align-middle"></th>
            <th class="align-middle">Tanggal</th>
            <th class="align-middle">No Transaksi</th>
            <th class="align-middle">Kode Kas</th>
            <th class="align-middle">Nama Pemesan</th>
            <th class="align-middle">Keterangan</th>
            <th class="align-middle">Status Bayar</th>
            <th class="align-middle">Masuk</th>
            <th class="align-middle">Keluar</th>
            <th class="align-middle">Kasir</th>
        </tr>
    </thead>
    <tfoot class="thead-light">
        <tr>
            <th class="align-middle"><input type="checkbox" id="checkTabel" aria-label="Tandai transaksi"></th>
            <th class="align-middle"></th>
            <th class="align-middle">Tanggal</th>
            <th class="align-middle">No Transaksi</th>
            <th class="align-middle">Kode Kas</th>
            <th class="align-middle">Nama Pemesan</th>
            <th class="align-middle">Keterangan</th>
            <th class="align-middle">Status Bayar</th>
            <th class="align-middle">Masuk</th>
            <th class="align-middle">Keluar</th>
            <th class="align-middle">Kasir</th>
        </tr>
    </tfoot>
    <tbody class="text-dark {{ ($kas->isEmpty() ? "table-borderless" : "") }}">
        @if($kas->isEmpty())
            <tr>
                <td colspan="11">Data tidak tersedia.</td>
            </tr>
        @else
            @foreach($kas as $item)
            <?php 
                $Transaksi = $DB::table('bayar_transaksis')->join('transaksis', 'transaksis.No_Transaksi', '=', 'bayar_transaksis.no_transaksi')->where('bayar_transaksis.no_bayar', '=', $item->Dokumen)->get();
                if($Transaksi->isEmpty())
                {
                    $no_transaksi = "-";
                    $nama_pemesan = "-";
                    $Status_Bayar = "-";
                } else {
                    foreach($Transaksi as $transaksi){
                        $no_transaksi = $transaksi->No_Transaksi;
                        $nama_pemesan = $transaksi->nama_pemesan;
                        $Status_Bayar = $transaksi->Status_Bayar;
                    }
                }

                if(empty($item->Dokumen)){
                    $no_transaksi = "-";
                    $nama_pemesan = "-";
                    $Status_Bayar = "-";
                }
            ?>
                <tr class="{{ $fungsi->classSB($Status_Bayar) }}">
                    <td><input type="checkbox" id="checkItem" aria-label="Tandai transaksi" data-id="{{ $item->Id_kas }}"></td>
                    <td>
                        <h6>
                            <a type="button" style="color: #4183c4;" id="edit-kas-btn" data-toggle="modal" data-target="#popup-editkas-{{ $item->Id_kas }}">Edit</a> | <a type="button" style="color: #4183c4;" id="remove-kas-btn" data-toggle="modal" data-target="#popup-hapuskas-{{ $item->Id_kas }}">Hapus</a>
                        </h6>
                    </td>
                    <td>{{ $item->Tanggal }}</td>
                    <td>{{ $no_transaksi }}</td>
                    <td>{{ $item->kode_kas }}</td>
                    <td>{{ $nama_pemesan }}</td>
                    <?php 
                    foreach($kodeakun as $ak){
                        if($item->forKas == $ak->kode){
                            $keterangan = $ak->nama;
                        }
                    }
                    ?>
                    <td>{{ ($item->Keterangan == "" ? $keterangan : $item->Keterangan ) }}</td>
                    <td>{{ $Status_Bayar }}</td>
                    <td>@if($item->Masuk == 0) - @else {{ $fungsi->rupiah($item->Masuk) }} @endif</td>
                    <td>@if($item->Keluar == 0) - @else {{ $fungsi->rupiah($item->Keluar) }} @endif</td>
                    <td>{{ $item->ModifiedBy }}</td>
                </tr>

                <?php 
                    $popupKas = ($item->Masuk !== 0 ? "Kas Masuk" : "Kas Keluar");
                ?>
                <div class="modal fade popup-editkas" id="popup-editkas-{{ $item->Id_kas}}" tabindex="-1" aria-labelledby="popup-editkas-label-{{ $item->Id_kas}}" aria-hidden="true" data-id="{{ $item->Id_kas }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="popup-editkas-label-{{ $item->Id_kas}}">{{ $popupKas }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="kodekas">Kode Kas</label>
                                    </div>
                                    <select class="custom-select" id="kodekas" name="kodekas" value="{{ $item->kode_kas }}">
                                        <option {{ ($item->kode_kas == "" ? "selected" : "") }}>Kode Kas</option>
                                        @foreach($kodekas as $kas)
                                        <option value="{{ $kas->nama }}" {{ ($item->kode_kas == $kas->nama ? "selected" : "") }}>{{ $kas->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="tglkas">Tanggal</label>
                                    </div>
                                    <div id="tglkas" class="form-control" style="display: flex;flex-direction: row;align-items: center; cursor: pointer;">
                                        <i class="far fa-calendar-alt mr-2"></i>&nbsp;
                                        <span id="tglinput" data-tgl-kas="{{ $item->Tanggal }}">{{ $item->Tanggal }}</span>
                                        <i class="fa fa-caret-down ml-auto"></i>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="Tujuan Kas">Tujuan Kas</label>
                                    </div>
                                    <select class="custom-select" id="tujuankas" name="tujuankas" value="{{ $item->forKas }}">
                                        <option {{ ($item->forKas == "" ? "selected" : "") }}>Pilih Tujuan Kas</option>
                                        @foreach($kodeakun as $akun)
                                        <option value="{{ $akun->kode }}" {{ ($item->forKas == $akun->kode ? "selected" : "") }}>{{ $akun->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Jumlah" id="input-harga-live" name="ubahkas" value="{{ ($item->Masuk !== 0 ? $fungsi->rupiahNoSymbol($item->Masuk) : $fungsi->rupiahNoSymbol($item->Keluar)) }}">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="keterangan-item">Keterangan</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Keterangan" name="keteranganKas" value="{{ $item->Keterangan }}" {{ ($item->forKas == 400 ? "disabled" : "") }}>{{ $item->Keterangan }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="ubah-kas">Ubah</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-ubahkas-popup">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade popup-hapuskas" id="popup-hapuskas-{{ $item->Id_kas }}" tabindex="-1" role="dialog" aria-labelledby="popup-hapuskas-label-{{ $item->Id_kas }}" aria-hidden="true" data-id="{{ $item->Id_kas }}">
                    <div class="modal-dialog modal-sm modal-notify modal-danger" style="max-width: 300px;">
                        <div class="modal-content text-center">
                            <div class="modal-header d-flex justify-content-center">
                                <p class="heading">Yakin ingin menghapusnya?</p>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="alasanhapuskas">Alasan</label>
                                    </div>
                                    <textarea class="form-control" aria-label="alasanhapuskas" name="alasanhapuskas"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer flex-center ml-auto mr-auto">
                                <a type="button" class="btn btn-outline-danger" id="hapus-kas">Hapus</a>
                                <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="close-hapuskas-popup">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </tbody>
</table>
@else
<div id="penjualanTable" data-for="kas">
    @if($kas->isEmpty())
    <div class="text-center text-bold">Data tidak tersedia.</div>
    @else
    @foreach($kas as $item)
    <?php 
        $Transaksi = $DB::table('bayar_transaksis')->join('transaksis', 'transaksis.No_Transaksi', '=', 'bayar_transaksis.no_transaksi')->where('bayar_transaksis.no_bayar', '=', $item->Dokumen)->get();
        foreach($Transaksi as $transaksi){
            $no_transaksi = $transaksi->No_Transaksi;
            $nama_pemesan = $transaksi->nama_pemesan;
            $Status_Bayar = $transaksi->Status_Bayar;
        }

        if($item->Masuk == 0){
            $MasukKeluar = "Keluar: " . $fungsi->rupiah($item->Keluar);
        } else {
            $MasukKeluar = "Masuk: " . $fungsi->rupiah($item->Masuk);
        }

        if(empty($item->Dokumen)){
            $no_transaksi = "<b>Pengeluaran</b>";
            $NamaStatus = "";
            $Status_Bayar = "-";
        } else {
            $no_transaksi = "<b>Pemasukan " . $no_transaksi . "</b>";
            $NamaStatus = '<div class="bion-content-text">Nama Pemesan: ' . $nama_pemesan . '</div>
                <div class="bion-content-text">Status Bayar: '. $Status_Bayar . '</div>';
        }
    ?>
    
    <div class="bion-container mb-4">
        <div class="bion-content">
            <div class="bion-content-head {{ $fungsi->classSBMobile($Status_Bayar) }}">
                <div class="bion-content-text bion-content-title" style="white-space: initial;width: calc(100%/2);">{!! $no_transaksi !!}</div>
                <div class="bion-content-text" style="white-space: initial;width: calc(100%/2);">
                    {{ date("d", strtotime($item->Tanggal)) . " " . $fungsi->convertBulanShort(date("M", strtotime($item->Tanggal))) . " " . date("Y", strtotime($item->Tanggal)) . " " . date("H:i", strtotime($item->Tanggal))}}
                </div>
            </div>
            <div class="bion-content-body">
                {!! $NamaStatus !!}
                <?php 
                foreach($kodeakun as $ak){
                    if($item->forKas == $ak->kode){
                        $keterangan = $ak->nama;
                    }
                }
                ?>
                <div class="bion-content-text">Keterangan: {{ ($item->Keterangan == "" ? $keterangan : $item->Keterangan ) }}</div>
                <div class="bion-content-text">{{ $MasukKeluar }}</div>
                <div class="bion-content-text">Kasir: {{ $item->ModifiedBy }}</div>
            </div>
        </div>
        <div class="d-flex flex-row mr-2 ml-2 mb-3" style="margin: 0 20px 20px 20px;">
            <button type="button" class="btn btn-outline-primary text-uppercase rounded-pill w-100 mr-2" id="edit-kas-btn" data-toggle="modal" data-target="#popup-editkas-{{ $item->Id_kas }}">Edit</button>
            <button type="button" class="btn btn-outline-danger text-uppercase rounded-pill w-100 ml-2" id="remove-kas-btn" data-toggle="modal" data-target="#popup-hapuskas-{{ $item->Id_kas }}">Hapus</button>
        </div>
    </div>

    <?php 
        $popupKas = ($item->Masuk !== 0 ? "Kas Masuk" : "Kas Keluar");
    ?>
    <div class="modal fade popup-editkas" id="popup-editkas-{{ $item->Id_kas}}" tabindex="-1" aria-labelledby="popup-editkas-{{ $item->Id_kas}}" aria-hidden="true" data-id="{{ $item->Id_kas }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup-editkas-{{ $item->Id_kas}}">{{ $popupKas }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="kodekas">Kode Kas</label>
                        </div>
                        <select class="custom-select" id="kodekas" name="kodekas" value="{{ $item->kode_kas }}">
                            <option {{ ($item->kode_kas == "" ? "selected" : "") }}>Kode Kas</option>
                            @foreach($kodekas as $kas)
                            <option value="{{ $kas->nama }}" {{ ($item->kode_kas == $kas->nama ? "selected" : "") }}>{{ $kas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="tglkas">Tanggal</label>
                        </div>
                        <div id="tglkas" class="form-control" style="display: flex;flex-direction: row;align-items: center; cursor: pointer;">
                            <i class="far fa-calendar-alt mr-2"></i>&nbsp;
                            <span id="tglinput" data-tgl-kas="{{ $item->Tanggal }}">{{ $item->Tanggal }}</span>
                            <i class="fa fa-caret-down ml-auto"></i>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="Tujuan Kas">Tujuan Kas</label>
                        </div>
                        <select class="custom-select" id="tujuankas" name="tujuankas" value="{{ $item->forKas }}">
                            <option {{ ($item->forKas == "" ? "selected" : "") }}>Pilih Tujuan Kas</option>
                            @foreach($kodeakun as $akun)
                            <option value="{{ $akun->kode }}" {{ ($item->forKas == $akun->kode ? "selected" : "") }}>{{ $akun->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control" aria-label="Jumlah" id="input-harga-live" name="tambahkas" value="{{ ($item->Masuk !== 0 ? $fungsi->rupiahNoSymbol($item->Masuk) : $fungsi->rupiahNoSymbol($item->Keluar)) }}">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="keterangan-item">Keterangan</span>
                        </div>
                        <textarea class="form-control" aria-label="Keterangan" name="keteranganKas" value="{{ $item->Keterangan }}" {{ ($item->forKas == 400 ? "disabled" : "") }}>{{ $item->Keterangan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="ubah-kas">Ubah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-ubahkas-popup">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade popup-hapuskas" id="popup-hapuskas-{{ $item->Id_kas }}" tabindex="-1" role="dialog" aria-labelledby="popup-hapuskas-label-{{ $item->Id_kas }}" aria-hidden="true" data-id="{{ $item->Id_kas }}">
        <div class="modal-dialog modal-sm modal-notify modal-danger" style="max-width: 300px;">
            <div class="modal-content text-center">
                <div class="modal-header d-flex justify-content-center">
                    <p class="heading">Yakin ingin menghapusnya?</p>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="alasanhapuskas">Alasan</label>
                        </div>
                        <textarea class="form-control" aria-label="alasanhapuskas" name="alasanhapuskas"></textarea>
                    </div>
                </div>
                <div class="modal-footer flex-center ml-auto mr-auto">
                    <a type="button" class="btn btn-outline-danger" id="hapus-kas">Hapus</a>
                    <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="close-hapuskas-popup">Batal</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endif