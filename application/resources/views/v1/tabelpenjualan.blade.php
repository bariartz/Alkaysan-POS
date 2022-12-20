<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
$MasterKas = new App\Models\MasterKas;
$kodekas = $MasterKas::orderBy("nama", "ASC")->get();
if($agent->isDesktop()){ ?>
<table id="penjualanTable" data-page-number="1" class="table text-center table-bordered table-hover table-responsive-sm" style="width:100%" data-for="penjualan">
    <thead class="thead-light">
        <tr>
            <th class="align-middle"><input type="checkbox" id="checkTabel" aria-label="Tandai transaksi"></th>
            <th class="align-middle">No Transaksi</th>
            <th class="align-middle">Nama Pemesan</th>
            <th class="align-middle">Tanggal Transaksi</th>
            <th class="align-middle">Status Bayar</th>
            <th class="align-middle">Status Transaksi</th>
            <th class="align-middle">Total QTY</th>
            <th class="align-middle">Total Item</th>
            <th class="align-middle">Grandtotal</th>
            <th class="align-middle">Total Bayar</th>
            <th class="align-middle">Sisa Bayar</th>
            <th class="align-middle">CS</th>
        </tr>
    </thead>
    <tfoot class="thead-light">
        <tr>
            <th class="align-middle"><input type="checkbox" id="checkTabel" aria-label="Tandai transaksi"></th>
            <th class="align-middle">No Transaksi</th>
            <th class="align-middle">Nama Pemesan</th>
            <th class="align-middle">Tanggal Transaksi</th>
            <th class="align-middle">Status Bayar</th>
            <th class="align-middle">Status Transaksi</th>
            <th class="align-middle">Total QTY</th>
            <th class="align-middle">Total Item</th>
            <th class="align-middle">Grandtotal</th>
            <th class="align-middle">Total Bayar</th>
            <th class="align-middle">Sisa Bayar</th>
            <th class="align-middle">CS</th>
        </tr>
    </tfoot>
    <tbody class="text-dark {{ ($transaksi->isEmpty() ? "table-borderless" : "") }}">
        @if($transaksi->isEmpty())
            <tr>
                <td colspan="12">Data hari ini masih kosong. Jika ingin mengambil data sebelumnya silahkan ubah tanggal.</td>
            </tr>
        @else
            @foreach($transaksi as $item)
                <tr class="{{ $fungsi->classSB($item->Status_Bayar) . (strtolower($item->Status_Transaksi) == 'sudah diambil' ? ' table-danger' : '') }}">
                    <td><input type="checkbox" id="checkItem" aria-label="Tandai transaksi" data-transaksi="{{ $item->No_Transaksi }}"></td>
                    <td>
                        {{ $item->No_Transaksi }}
                        <h6>
                            <a href="/{{ $cabang }}/penjualan/transaksi/edit/{{ $item->No_Transaksi }}">Edit</a> | <a type="button" style="color: #4183c4;" id="hapus-btn" data-toggle="modal" data-target="#popup-hapus-{{ $item->No_Transaksi }}">Hapus</a> | @if($item->Status_Bayar == 'Lunas') <a class="text-decoration-none text-reset" style="cursor: default;">Lunas</a> @else <a type="button" style="color: #4183c4;" id="bayar-btn" data-toggle="modal" data-target="#popup-bayar-{{ $item->No_Transaksi }}">Bayar</a> @endif | <a type="button" style="color: #4183c4;" id="status-btn" data-toggle="modal" data-target="#popup-status-{{ $item->No_Transaksi }}">Status</a> | <a href="/{{ $cabang }}/penjualan/transaksi/cetak/struk/{{ $item->No_Transaksi }}" target="_blank">Cetak</a>
                        </h6>
                    </td>
                    <td>{{ $item->nama_pemesan }}</td>
                    <td>{{ $item->Tanggal_Transaksi }}</td>
                    <td>{{ $item->Status_Bayar }}</td>
                    <td>{{ $item->Status_Transaksi }}</td>
                    <td>{{ (float)$item->total_qty }}</td>
                    <td>{{ (float)$item->total_item }}</td>
                    <td>{{ $fungsi->rupiah($item->net_total_sales) }}</td>
                    <td>{{ $fungsi->rupiah($item->total_bayar) }}</td>
                    <td>{{ $fungsi->rupiah($item->sisa_bayar) }}</td>
                    <td>{{ $item->nama_cs }}</td>
                </tr>
                <div class="modal fade popup-bayar-transaksi" id="popup-bayar-{{ $item->No_Transaksi }}" tabindex="-1" data-no-transaksi="{{  $item->No_Transaksi }}" aria-labelledby="popup-bayar-label-{{ $item->No_Transaksi }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="popup-bayar-label-{{ $item->No_Transaksi }}">Bayar Invoice {{ $item->No_Transaksi }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="bion-box-title">Riwayat Pembayaran</div>
                                <div class="bion-box-with-timeline mb-4">
                                    <?php
                                    $riwayatBayar = $fungsi->riwayatTransaksi($item->No_Transaksi);
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
                                    <input type="text" class="form-control total-harga" aria-label="Jumlah" id="input-harga-live" name="totalbayar-{{ $item->No_Transaksi }}" disabled value="{{ $fungsi->rupiahNoSymbol($item->net_total_sales) }}">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="text-center" style="padding: 0.5rem 0rem;margin-right: 0.88rem;" id="sisakembalian">Sisa Bayar</div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control kotak-sisa-bayar" aria-label="Jumlah" id="input-harga-live" data-sisa-bayar="{{ $fungsi->rupiahNoSymbol($item->sisa_bayar) }}" name="sisabayar-{{ $item->No_Transaksi }}" disabled value="{{ $fungsi->rupiahNoSymbol($item->sisa_bayar) }}">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control kotak-bayar mr-3" aria-label="Jumlah" id="input-harga-live" name="bayar-{{ $item->No_Transaksi }}">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Kode Kas</span>
                                    </div>
                                    <select class="custom-select" id="kodekas" name="kodekas-{{ $item->No_Transaksi }}">
                                        <option>Kode Kas</option>
                                        @foreach($kodekas as $kas)
                                        <option value="{{ $kas->nama }}" {{ ($kas->nama == "TUNAI" ? "selected" : "") }}>{{ $kas->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary"  id="tambah-bayar-btn">Bayar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-bayar-popup">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade popup-status-transaksi" id="popup-status-{{ $item->No_Transaksi }}" tabindex="-1" data-no-transaksi="{{  $item->No_Transaksi }}" aria-labelledby="popup-status-label-{{ $item->No_Transaksi }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="popup-status-label-{{ $item->No_Transaksi }}">Status Transaksi {{ $item->No_Transaksi }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Status</span>
                                    </div>
                                    <select class="custom-select" id="statustransaksi" name="statustransaksi" {{ ($item->Status_Transaksi == "Sudah diambil" ? "disabled" : "") }}>
                                        <option>Pilih Status</option>
                                        <option value="Belum diproses" {{ ($item->Status_Transaksi == "Belum diproses" ? "selected" : "") }}>Belum diproses</option>
                                        <option value="Sedang didesain" {{ ($item->Status_Transaksi == "Sedang didesain" ? "selected" : "") }}>Sedang didesain</option>
                                        <option value="Proses cetak" {{ ($item->Status_Transaksi == "Proses cetak" ? "selected" : "") }}>Proses cetak</option>
                                        <option value="Selesai dicetak" {{ ($item->Status_Transaksi == "Selesai dicetak" ? "selected" : "") }}>Selesai dicetak</option>
                                        <option value="Sudah diambil" {{ ($item->Status_Transaksi == "Sudah diambil" ? "selected" : "") }}>Sudah diambil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary"  id="update-status-btn">Update Status</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-status-popup">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade popup-hapus-transaksi" id="popup-hapus-{{ $item->No_Transaksi }}" tabindex="-1" role="dialog" aria-labelledby="popup-hapus-label-{{ $item->No_Transaksi }}" aria-hidden="true" data-no-transaksi="{{ $item->No_Transaksi }}">
                    <div class="modal-dialog modal-sm modal-notify modal-danger" style="max-width: 300px;">
                        <div class="modal-content text-center">
                            <div class="modal-header d-flex justify-content-center">
                                <p class="heading">Yakin ingin menghapusnya?</p>
                            </div>
                            <div class="modal-footer flex-center ml-auto mr-auto">
                                <a type="button" class="btn btn-outline-danger" id="hapus-transaksi-btn">Hapus</a>
                                <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="close-hapus-popup">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </tbody>
</table>
<?php } else { ?>
    @foreach($transaksi as $item)
    <div class="bion-container mb-4" data-for="penjualan">
        <div class="bion-content">
            <div class="bion-content-head {{ $item->Status_Bayar == 'Lunas' ? "table-success" : "table-warning" }} align-items-center" type="button" data-toggle="collapse" data-target="#penjualan{{ $item->No_Transaksi }}" aria-expanded="true" aria-controls="penjualan{{ $item->No_Transaksi }}">
                <div class="bion-content-text bion-content-title mr-auto">{{ $item->No_Transaksi }}</div>
                <div class="bion-content-text ml-auto">
                    {{ date("d", strtotime($item->Tanggal_Transaksi)) . " " . $fungsi->convertBulanShort(date("M", strtotime($item->Tanggal_Transaksi))) . " " . date("Y", strtotime($item->Tanggal_Transaksi)) . " " . date("H:i", strtotime($item->Tanggal_Transaksi))}}
                </div>
                <i class="fas fa-chevron-down ml-2" id="collapsearrow"></i>
            </div>
            <div class="bion-content-body collapse" id="penjualan{{ $item->No_Transaksi }}">
                <div class="bion-content-text">Nama: <b>{{ $item->nama_pemesan }}</b></div>
                <div class="bion-content-text">Status Bayar: {{ $item->Status_Bayar }}</div>
                <div class="bion-content-text">Status Transaksi: {{ $item->Status_Transaksi }}</div>
                <div class="bion-content-text">Total QTY: {{ (float)$item->total_qty }}</div>
                <div class="bion-content-text">Total Item: {{ (float)$item->total_item }}</div>
                <div class="bion-content-text">Grandtotal: {{ $fungsi->rupiah($item->net_total_sales) }}</div>
                <div class="bion-content-text">Total Bayar: {{ $fungsi->rupiah($item->total_bayar) }}</div>
                <div class="bion-content-text">Sisa Bayar: {{ $fungsi->rupiah($item->sisa_bayar) }}</div>
                <div class="bion-menu-button mt-3">
                    <a href="/{{ $cabang }}/penjualan/transaksi/edit/{{ $item->No_Transaksi }}" type="button" class="btn btn-outline-primary mr-auto text-uppercase rounded-pill" style="width: 85px;">Edit</a>
                    <button type="button" class="btn btn-outline-danger mr-auto ml-auto text-uppercase rounded-pill" style="width: 85px;" id="hapus-btn" data-toggle="modal" data-target="#popup-hapus-{{ $item->No_Transaksi }}">Hapus</button>
                    @if($item->Status_Bayar == 'Lunas') <button type="button" class="btn btn-outline-success ml-auto text-uppercase rounded-pill" style="width: 85px;" disabled>Bayar</button> @else <button type="button" class="btn btn-outline-success ml-auto text-uppercase rounded-pill" id="bayar-btn" data-toggle="modal" data-target="#popup-bayar-{{ $item->No_Transaksi }}" style="width: 85px;">Bayar</button> @endif
                    <button type="button" class="btn btn-outline-info text-uppercase rounded-pill" style="width: 85px; margin-top: 5px; margin-right: 27px;" id="status-btn" data-toggle="modal" data-target="#popup-status-{{ $item->No_Transaksi }}">Status</button>
                    <a href="/{{ $cabang }}/penjualan/transaksi/cetak/struk/{{ $item->No_Transaksi }}" type="button" class="btn btn-outline-secondary text-uppercase rounded-pill" style="width: 85px; margin-top: 5px;" target="_blank">Cetak</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade popup-bayar-transaksi" id="popup-bayar-{{ $item->No_Transaksi }}" tabindex="-1" data-no-transaksi="{{  $item->No_Transaksi }}" aria-labelledby="popup-bayar-label-{{ $item->No_Transaksi }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup-bayar-label-{{ $item->No_Transaksi }}">Bayar Invoice {{ $item->No_Transaksi }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="bion-box-title">Riwayat Pembayaran</div>
                    <div class="bion-box-with-timeline mb-4">
                        <?php
                        $riwayatBayar = $fungsi->riwayatTransaksi($item->No_Transaksi);
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
                        <input type="text" class="form-control total-harga" aria-label="Jumlah" id="input-harga-live" name="totalbayar-{{ $item->No_Transaksi }}" disabled value="{{ $fungsi->rupiahNoSymbol($item->net_total_sales) }}">
                    </div>
                    <div class="input-group mb-3">
                        <div class="text-center" style="padding: 0.5rem 0rem;margin-right: 0.88rem;" id="sisakembalian">Sisa Bayar</div>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control kotak-sisa-bayar" aria-label="Jumlah" id="input-harga-live" data-sisa-bayar="{{ $fungsi->rupiahNoSymbol($item->sisa_bayar) }}" name="sisabayar-{{ $item->No_Transaksi }}" disabled value="{{ $fungsi->rupiahNoSymbol($item->sisa_bayar) }}">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control kotak-bayar mr-3" aria-label="Jumlah" id="input-harga-live" name="bayar-{{ $item->No_Transaksi }}">

                        <div class="input-group-prepend">
                            <span class="input-group-text">Kode Kas</span>
                        </div>
                        <select class="custom-select" id="kodekas" name="kodekas-{{ $item->No_Transaksi }}">
                            <option>Kode Kas</option>
                            @foreach($kodekas as $kas)
                            <option value="{{ $kas->nama }}" {{ ($kas->nama == "TUNAI" ? "selected" : "") }}>{{ $kas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  id="tambah-bayar-btn">Bayar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-bayar-popup">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade popup-status-transaksi" id="popup-status-{{ $item->No_Transaksi }}" tabindex="-1" data-no-transaksi="{{  $item->No_Transaksi }}" aria-labelledby="popup-status-label-{{ $item->No_Transaksi }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup-status-label-{{ $item->No_Transaksi }}">Status Transaksi {{ $item->No_Transaksi }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Status</span>
                        </div>
                        <select class="custom-select" id="statustransaksi" name="statustransaksi" {{ ($item->Status_Transaksi == "Sudah diambil" ? "disabled" : "") }}>
                            <option>Pilih Status</option>
                            <option value="Belum diproses" {{ ($item->Status_Transaksi == "Belum diproses" ? "selected" : "") }}>Belum diproses</option>
                            <option value="Sedang didesain" {{ ($item->Status_Transaksi == "Sedang didesain" ? "selected" : "") }}>Sedang didesain</option>
                            <option value="Proses cetak" {{ ($item->Status_Transaksi == "Proses cetak" ? "selected" : "") }}>Proses cetak</option>
                            <option value="Selesai dicetak" {{ ($item->Status_Transaksi == "Selesai dicetak" ? "selected" : "") }}>Selesai dicetak</option>
                            <option value="Sudah diambil" {{ ($item->Status_Transaksi == "Sudah diambil" ? "selected" : "") }}>Sudah diambil</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  id="update-status-btn">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-status-popup">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade popup-hapus-transaksi" id="popup-hapus-{{ $item->No_Transaksi }}" tabindex="-1" role="dialog" aria-labelledby="popup-hapus-label-{{ $item->No_Transaksi }}" aria-hidden="true" data-no-transaksi="{{ $item->No_Transaksi }}">
        <div class="modal-dialog modal-sm modal-notify modal-danger" style="max-width: 300px;">
            <div class="modal-content text-center">
                <div class="modal-header d-flex justify-content-center">
                    <p class="heading">Yakin ingin menghapusnya?</p>
                </div>
                <div class="modal-footer flex-center ml-auto mr-auto">
                    <a type="button" class="btn btn-outline-danger" id="hapus-transaksi-btn">Hapus</a>
                    <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="close-hapus-popup">Batal</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
<?php } ?>