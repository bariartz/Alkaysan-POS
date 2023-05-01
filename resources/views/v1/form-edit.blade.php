<?php
$masteritem = new App\Models\MasterItem;
$produkitem = new App\Models\ProdukHarga;
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
$MasterKas = new App\Models\MasterKas;
$kodekas = $MasterKas::orderBy("nama", "ASC")->get();
$index = $masteritem::all();
$itemId = 1;
?>
<div class="card shadow mb-4">
    <div class="card-body">
        <form id="formTransaksi" name="formTransaksi">
            @csrf
            <div id="hidden-input">
                <input type="hidden" name="nama_cs" value="{{ $nama_user }}"/>
                <input type="hidden" name="No_Transaksi" value="{{ $transaksi->No_Transaksi }}"/>
                <input type="hidden" name="Status_Bayar" value="{{ $transaksi->Status_Bayar }}"/>
                <input type="hidden" name="Status_Transaksi" value="{{ $transaksi->Status_Transaksi }}"/>
                <input type="hidden" name="No_Pemesan" value="{{ $transaksi->no_pemesan }}"/>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div id="alert-form-empty"></div>
                    Nama Pemesan*
                    <input type="text" class="form-control" name="nama_pemesan" value="{{ $transaksi->nama_pemesan }}" required>

                    Alamat Pemesan
                    <input type="text" class="form-control" name="alamat_pemesan" value="{{ $transaksi->alamat_pemesan }}">

                    No hp/wa Pemesan*
                    <input type="text" class="form-control" name="telepon_pemesan" value="{{ $transaksi->telepon_pemesan }}" required>
                    <div class="dataTables_length mr-5" id="select-member">
                        <label class="d-flex flex-row">
                            Member*
                            <div class="selection ui dropdown ml-3" tabindex="0">
                                <select name="member_pemesan" required>
                                    <option value="0" {{ ($transaksi->membership === "" ? "selected" : "") }}>Pilih Member</option>
                                    <option value="1" {{ ($transaksi->membership === "UMUM" ? "selected" : "") }}>UMUM</option>
                                    <option value="2" {{ ($transaksi->membership === "STUDIO" ? "selected" : "") }}>STUDIO</option>
                                </select>
                                <i class="dropdown icon"></i>
                                <div class="text">{{ $transaksi->membership }}</div>
                                <div class="menu transition" tabindex="-1">
                                    <div class="item {{ ($transaksi->membership === "" ? "active selected" : "") }}" data-value="0" style="width: 100%;">Pilih Member</div>
                                    <div class="item {{ ($transaksi->membership === "UMUM" ? "active selected" : "") }}" data-value="1" style="width: 100%;">UMUM</div>
                                    <div class="item {{ ($transaksi->membership === "STUDIO" ? "active selected" : "") }}" data-value="2" style="width: 100%;">STUDIO</div>
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
                                <div id="harga-subtotal" data-harga-subtotal="{{ $transaksi->total_sales }}">Subtotal: {{ $fungsi->rupiah($transaksi->total_sales) }}</div>
                                <div id="total-item" data-total-item="{{ (float)$transaksi->total_item }}">Total Item: {{ (float)$transaksi->total_item }}</div>
                                <div id="total-qty" data-total-qty="{{ (float)$transaksi->total_qty }}">Total QTY: {{ (float)$transaksi->total_qty }}</div>
                                <hr>
                                <div id="total-harga" data-total-harga="{{ (float)$transaksi->net_total_sales }}">Total: {{ $fungsi->rupiah($transaksi->net_total_sales) }}</div>
                                <div id="total-bayar" data-total-bayar="{{ (float)$transaksi->total_bayar }}">Telah dibayar: {{ $fungsi->rupiah($transaksi->total_bayar) }}</div>
                            </div>
                        </div>
                    </div>

                    @if($agent->isMobile())
                        <div class="bion-menu-button mt-4">
                            <button type="button" class="btn btn-outline-primary mr-auto" id="add-item-btn" data-toggle="modal" data-target="#popup-select-item">+ Tambah Item</button>
                            <button type="button" class="btn btn-outline-danger ml-auto mr-auto" id="ongkir-btn" data-toggle="modal" data-target="#popup-ongkir">Ongkir</button>
                            <button type="button" class="btn btn-outline-success ml-auto" id="diskon-btn" data-toggle="modal" data-target="#popup-diskon">Diskon</button>
                        </div>
                    @else
                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-primary mr-2" id="add-item-btn" data-toggle="modal" data-target="#popup-select-item">+ Tambah Item</button>
                            <button type="button" class="btn btn-outline-danger ml-2 mr-2" id="ongkir-btn" data-toggle="modal" data-target="#popup-ongkir">Ongkir</button>
                            <button type="button" class="btn btn-outline-success ml-2" id="diskon-btn" data-toggle="modal" data-target="#popup-diskon">Diskon</button>
                        </div>
                    @endif
                    <div id="list-item-container">
                        @foreach($itemtransaksi as $item)
                            <div class="card shadow mt-2" id="item-container">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm flex-grow-0">
                                            <i class="fas fa-fw fa-trash-alt" style="cursor: pointer;"></i>
                                        </div>
                                        <div class="col-sm">
                                            <div id="item-detail">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span id="nama-produk" data-nama-produk="{{ $item->Nama_Produk }}" data-kode-produk="{{ $item->Kode_Produk }}" data-cost="{{ (float)$item->cost }}" data-isdimensi="{{ $item->isdimensi }}" data-harga-produk="{{ $item->sales }}" data-panjang="{{ (float)$item->p }}" data-lebar="{{ (float)$item->l }}" data-satuan="{{ $item->satuan }}"
                                                            data-keterangan="{{ $item->keterangan }}" data-qty="{{ (float)$item->Qty }}" data-no-item="{{ $item->No_ItemTransaksi }}" data-subtotal="{{ (float)$item->subtotal_sales }}">
                                                            {{ $item->Nama_Produk }}
                                                            @if($item->isdimensi == 1)
                                                                {{ "UK. " . (float)$item->p . "x" . (float)$item->l . $item->satuan }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="panjang-item" style="display: none;">P*</span>
                                                    </div>
                                                    <input type="number" class="form-control" aria-label="Panjang" name="panjangItem" required=""
                                                        style="transition: all 200ms ease-out 0s; display: none;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="lebar-item" style="display: none;">L*</span>
                                                    </div>
                                                    <input type="number" class="form-control" aria-label="Lebar" name="lebarItem" required="" style="transition: all 200ms ease-out 0s; display: none;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text disabled" id="satuan-item" style="display: none;">{{ $item->satuan }}</span>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="" id="qty-item" data-qty="{{ $item->Qty }}">QTY: {{ $item->Qty }}</span>
                                                    </div>
                                                    <input type="number" class="form-control" aria-label="Jumlah Item" name="qtyItem" required="" style="transition: all 200ms ease-out 0s; display: none;">
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="" id="harga-item" data-harga-produk="{{ (float)$item->sales }}" data-subtotal="{{ (float)$item->subtotal_sales }}">Harga: {{ $fungsi->rupiah($item->subtotal_sales) }}</span>
                                                    </div>
                                                    <input type="text" class="form-control" aria-label="Harga" name="hargaItem" id="input-harga-live" disabled="" required="" value="{{ $fungsi->rupiahNoSymbol($item->subtotal_sales) }}" style="transition: all 200ms ease-out 0s; display: none;">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-outline-primary text-uppercase" id="nego-btn" style="display: none;">
                                                            <i class="fas fa-fw fa-tags"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="" id="keterangan-item" data-keterangan-item="{{ $item->keterangan }}">Keterangan: <br>{{ $item->keterangan }}</span>
                                                    </div>
                                                    <textarea class="form-control" aria-label="Keterangan" name="keteranganItem" style="display: none;"></textarea>
                                                </div>
                                            </div>
                                            <div id="item-menu-btn">
                                                <button type="button" class="btn btn-outline-warning mt-2 text-uppercase" id="edit-item-btn" data-edit-item="0">Edit Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                        <input type="text" class="form-control" id="searchTable" placeholder="Cari nama produk">
                    </div>
                </div>
                <table id="penjualanTable" class="table text-center table-bordered table-hover table-responsive-sm" style="width:100%" data-for="penjualan">
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
                    <input type="text" class="form-control" aria-label="Jumlah" id="input-harga-live" name="hargaOngkir" value="{{ $fungsi->rupiahNoSymbol($transaksi->ongkir) }}">
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
                    <input type="text" class="form-control" aria-label="Jumlah" id="input-harga-live" name="hargaDiskon" value="{{ $fungsi->rupiahNoSymbol($transaksi->potongan) }}">
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
                        <?php
                        $riwayatBayar = $fungsi->riwayatTransaksi($transaksi->No_Transaksi);
                        ?>
                        @if(count($riwayatBayar['bayartransaksi']) == 0)
                            <ul class="bion-timeline">
                                <li>
                                    <p>Belum ada riwayat pembayaran</p>
                                </li>
                            </ul>
                        @else
                            @foreach($riwayatBayar['bayartransaksi'] as $bayartransaksi)
                                <ul class="bion-timeline">
                                    <li>
                                        <div class="bion-timeline-header d-flex">
                                            <div class="bion-timeline-title">{{ $bayartransaksi->no_bayar }}</div>
                                            <div class="bion-timeline-date ml-3">{{ $bayartransaksi->tgl_input }}</div>
                                        </div>
                                        <p>Pembayaran dengan no invoice {{ $bayartransaksi->no_transaksi }} sebesar {{ $fungsi->rupiah($bayartransaksi->jumlah) }} via {{ $bayartransaksi->Keterangan }}.</p>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="text-center mr-2" style="padding: 0.5rem 0rem;margin-right: 0.5rem;">Total Harga</div>
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control total-harga" aria-label="Jumlah" id="input-harga-live" name="totalbayar" disabled value="{{ $fungsi->rupiahNoSymbol($transaksi->net_total_sales) }}">
                    </div>
                    <div class="input-group mb-3">
                        <div class="text-center" style="padding: 0.5rem 0rem;margin-right: 0.88rem;" id="sisakembalian">Sisa Bayar</div>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control kotak-sisa-bayar" aria-label="Jumlah" id="input-harga-live" data-sisa-bayar="{{ (float)$transaksi->sisa_bayar }}" name="sisabayar" disabled value="{{ $fungsi->rupiahNoSymbol($transaksi->sisa_bayar) }}">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control kotak-bayar mr-3" aria-label="Jumlah" id="input-harga-live" name="bayar">

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
<script>
    window.onload = () => {
        negoItem();
        removeItem();
        saveItem();
        editItem();
    }
</script>