@extends("layout.main")
@section('title', 'Produk')
@section("container")
<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
?>
<h1 class="h3 mb-2 text-gray-800">Produk</h1>
<div id="msgForm" class="{{ ($agent->isMobile() ? 'fixed-bottom w-100 mr-auto ml-auto' : 'fixed-top w-25') }}" style="{{ ($agent->isMobile() ? 'width: 85%!important; bottom: 5%;' : 'margin-left: auto;top: 10%;right: 1%;') }}"></div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
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
            <table class="table text-center table-bordered table-hover table-responsive-sm" id="table_MasterItem" width="100%" cellspacing="0" data-for="masteritem">
                <thead>
                    <tr>
                        <th class="align-middle">Nama Produk</th>
                        <th class="align-middle">Kategori</th>
                        <th class="align-middle">Satuan</th>
                        <th class="align-middle">Minimal Pembelian</th>
                        <th class="align-middle">Harga Umum</th>
                        <th class="align-middle">Harga Studio</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="align-middle">Nama Produk</th>
                        <th class="align-middle">Kategori</th>
                        <th class="align-middle">Satuan</th>
                        <th class="align-middle">Minimal Pembelian</th>
                        <th class="align-middle">Harga Umum</th>
                        <th class="align-middle">Harga Studio</th>
                    </tr>
                </tfoot>
                <tbody>
                    @if($data->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">Data tidak tersedia.</td>
                        </tr>
                    @else
                        @foreach($data as $item)
                        <?php
                            $produkHarga = new App\Models\ProdukHarga;
                            $fungsi = new App\Models\Fungsi;
                            $harga = $produkHarga::where(['Kode_Produk' => $item->Kode_Produk, 'min_pembelian' => $item->min_pembelian])->get();
                            $hargaUmum = "";
                            $hargaStudio = "";
                            foreach($harga as $items){
                                if($items->jenis_harga == "UMUM"){
                                    $hargaUmum = $fungsi->rupiah($items->harga);
                                    $hargaUmumNonRp = (float)$items->harga;
                                } else if($items->jenis_harga == "STUDIO"){
                                    $hargaStudio = $fungsi->rupiah($items->harga);
                                    $hargaStudioNonRp = (float)$items->harga;
                                }
                            }
                        ?>
                            <tr data-kode-produk="{{ $item->Kode_Produk }}">
                                <td class="align-middle" data-for="namaproduk" data-value="{{ $item->Nama_Produk }}">{{ $item->Nama_Produk }}</td>
                                <td class="align-middle" data-for="kategori" data-value="{{ $item->Kategori }}">{{ $item->Kategori }}</td>
                                <td class="align-middle" data-for="satuan" data-value="{{ $item->Satuan }}">{{ $item->Satuan }}</td>
                                <td class="align-middle" data-for="minpembelian" data-value="{{ $item->min_pembelian }}">{{ $item->min_pembelian }}</td>
                                <td class="align-middle" data-for="umum" data-value="{{ $hargaUmumNonRp }}">{{ $hargaUmum }}</td>
                                <td class="align-middle" data-for="studio" data-value="{{ $hargaStudioNonRp }}">{{ $hargaStudio }}</td>
                            </tr>
                        @endforeach
                    @endif
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
    </div>
</div>
@endsection
