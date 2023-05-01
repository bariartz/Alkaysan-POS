<?php
$masteritem = new App\Models\MasterItem;
$produkitem = new App\Models\ProdukHarga;
$transaksi = new App\Http\Controllers\TransaksiController;
$perusahaan = new App\Models\perusahaan;
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
$MasterKas = new App\Models\MasterKas;
$userController = new App\Http\Controllers\UserController;
$kodekas = $MasterKas::orderBy("nama", "ASC")->get();
$index = $masteritem::all();

$shortLinkCabang = request()->segment(1);
$dataPerusahaan = $userController->getBranch('qsearch=' . $shortLinkCabang);
$shortCabang = $dataPerusahaan['data'][0]['short_cabang'];

$newNoTransaksi = $transaksi->cekNoTransaksi();
if($newNoTransaksi === null){
    $newNoTransaksi = $shortCabang . "00001";
} else {
    $newNoTransaksi = $newNoTransaksi->No_Transaksi;
    $newNoTransaksi++;
}
?>
<div class="card shadow mb-4">
    <div class="card-body">
        <form id="formTransaksi" name="formTransaksi">
            @csrf
            <div id="hidden-input">
                <input type="hidden" name="nama_cs" value="{{ Auth::user()->nama_belakang_karyawan !== NULL ? Auth::user()->nama_depan_karyawan . ' ' . Auth::user()->nama_belakang_karyawan : Auth::user()->nama_depan_karyawan }}"/>
                <input type="hidden" name="No_Transaksi" value="{{ $newNoTransaksi }}"/>
                <input type="hidden" name="Status_Bayar" value="Belum Lunas"/>
                <input type="hidden" name="Status_Transaksi" value="Belum diproses"/>
            </div>
            <div class="row">
                <div class="col-sm" id="input-customer-data">
                    <div id="alert-form-empty"></div>
                    
                    Nama Pemesan*
                    <div class="d-flex flex-row align-items-center">
                        <input type="text" autocomplete="off" class="form-control" name="nama_pemesan" id="input-customer-box" required>
                    </div>
                    <div class="card shadow d-none" id="nameSearchResult">
                        <div class="d-flex flex-column mt-2 mb-2"></div>
                    </div>

                    Alamat Pemesan
                    <input type="text" autocomplete="off" class="form-control" name="alamat_pemesan" id="input-customer-box">

                    No hp/wa Pemesan*
                    <input type="tel" class="form-control" name="telepon_pemesan" id="input-customer-box" required>

                    <div class="dataTables_length mr-5" id="select-member">
                        <label class="d-flex flex-row">
                            Member*
                            <div class="selection ui dropdown ml-3" tabindex="0">
                                <select name="member_pemesan">
                                    <option value="">Pilih Member</option>
                                    <option value="1">UMUM</option>
                                    <option value="2">STUDIO</option>
                                </select>
                                <i class="dropdown icon"></i>
                                <div class="text">Pilih Member</div>
                                <div class="menu transition" tabindex="-1">
                                    <div class="item active selected" data-value="" style="width: 100%;">Pilih Member</div>
                                    <div class="item" data-value="1" style="width: 100%;">UMUM</div>
                                    <div class="item" data-value="2" style="width: 100%;">STUDIO</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="col-sm">
                    @if($agent->isMobile())
                    <hr>
                    @endif
                    <div class="order-summary-head">
                        <div class="row">
                            <div class="col-sm">
                                <div id="harga-subtotal" data-harga-subtotal="0">Subtotal: -</div>
                                <div id="total-item" data-total-item="0">Total Item: -</div>
                                <div id="total-qty" data-total-qty="0">Total QTY: -</div>
                                <hr>
                                <div id="total-harga" data-total-harga="0">Total: -</div>
                                <div id="total-bayar" data-total-bayar="0">Telah dibayar: -</div>
                            </div>
                        </div>
                    </div>

                    @if($agent->isMobile())
                        <div class="bion-menu-button mt-4">
                            <button type="button" class="btn btn-outline-primary mr-auto" id="add-item-btn" data-toggle="modal" data-target="#popup-select-item" disabled>+ Tambah Item</button>
                            <button type="button" class="btn btn-outline-danger ml-auto mr-auto" id="ongkir-btn" data-toggle="modal" data-target="#popup-ongkir">Ongkir</button>
                            <button type="button" class="btn btn-outline-success ml-auto" id="diskon-btn" data-toggle="modal" data-target="#popup-diskon">Diskon</button>
                        </div>
                    @else
                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-primary mr-2" id="add-item-btn" data-toggle="modal" data-target="#popup-select-item" disabled>+ Tambah Item</button>
                            <button type="button" class="btn btn-outline-danger ml-2 mr-2" id="ongkir-btn" data-toggle="modal" data-target="#popup-ongkir">Ongkir</button>
                            <button type="button" class="btn btn-outline-success ml-2" id="diskon-btn" data-toggle="modal" data-target="#popup-diskon">Diskon</button>
                        </div>
                    @endif
                    <div id="list-item-container"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="popup-select-item" tabindex="-1" aria-labelledby="popup-select-item-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-select-item-label">Tambah Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px; overflow: auto;">
                <div class="d-flex flex-row">
                    <div class="dataTables_length mr-5" id="penjualanTable_length">
                        <label>
                            Tampilkan
                            <div class="selection ui dropdown" tabindex="0" style="min-width: auto;">
                                <select name="penjualanTable_length" aria-controls="penjualanTable">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                </select>
                                <i class="dropdown icon"></i>
                                <div class="text">25</div>
                                <div class="menu transition" tabindex="-1">
                                    <div class="item active selected" data-value="25">25</div>
                                    <div class="item" data-value="50">50</div>
                                    <div class="item" data-value="100">100</div>
                                    <div class="item" data-value="250">250</div>
                                </div>
                            </div>
                            baris
                        </label>
                    </div>
    
                    <div class="d-flex flex-row align-items-center ml-auto mb-4" style="width: 300px;">
                        <i class="fas fa-search mr-2"></i>
                        <input type="text" autocomplete="off" class="form-control" id="searchTable" placeholder="Cari nama produk">
                    </div>
                </div>
                <table id="penjualanTable" class="table text-center table-bordered table-hover table-responsive-sm" style="width:100%;" data-for="penjualan">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class='pagination-container'>
                    <nav>
                        <ul class="pagination">
                            <li data-page="prev" class="page__btn">
                                <span class="fa fa-chevron-left">
                                    <span class="sr-only">(current)</span>
                                </span>
                            </li>
                            <li data-page="next" id="prev" class="page__btn">
                                <span class="fa fa-chevron-right">
                                    <span class="sr-only">(current)</span>
                                </span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-item-container">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popup-ongkir" tabindex="-1" aria-labelledby="popup-ongkir-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-ongkir-label">Ongkir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" autocomplete="off" class="form-control" aria-label="Jumlah" id="input-harga-live" name="hargaOngkir">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="tambahOngkir();" id="tambah-ongkir-btn">Tambah Ongkir</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-ongkir-popup">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popup-diskon" tabindex="-1" aria-labelledby="popup-diskon-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-diskon-label">Diskon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" autocomplete="off" class="form-control" aria-label="Jumlah" id="input-harga-live" name="hargaDiskon">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="tambahDiskon();" id="tambah-diskon-btn">Tambah Diskon</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-diskon-popup">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popup-bayar" tabindex="-1" aria-labelledby="popup-bayar-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-bayar-label">Bayar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bion-box-title">Riwayat Pembayaran</div>
                    <div class="bion-box-with-timeline mb-4">
                        <ul class="bion-timeline">
                            <li>
                                <p>Belum ada riwayat pembayaran</p>
                            </li>
                        </ul>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="text-center mr-2" style="padding: 0.5rem 0rem;margin-right: 0.5rem;">Total Harga</div>
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" autocomplete="off" class="form-control total-harga" aria-label="Jumlah" id="input-harga-live" name="totalbayar" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="text-center" style="padding: 0.5rem 0rem;margin-right: 0.88rem;" id="sisakembalian">Sisa Bayar</div>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" autocomplete="off" class="form-control kotak-sisa-bayar" aria-label="Jumlah" id="input-harga-live" data-sisa-bayar="0" name="sisabayar" disabled value="0">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" autocomplete="off" class="form-control kotak-bayar mr-3" aria-label="Jumlah" id="input-harga-live" name="bayar">

                        <div class="input-group-prepend">
                            <span class="input-group-text">Kode Kas</span>
                        </div>
                        <select class="custom-select" id="kodekas" name="kodekasbayar">
                            <option>Kode Kas</option>
                            @foreach($kodekas as $kas)
                            <option value="{{ $kas->nama }}" {{ ($kas->nama == "TUNAI" ? "selected" : "") }}>{{ $kas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="bayarTransaksiSingle();" id="tambah-bayar-btn">Bayar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-bayar-popup">Batal</button>
            </div>
        </div>
    </div>
</div>