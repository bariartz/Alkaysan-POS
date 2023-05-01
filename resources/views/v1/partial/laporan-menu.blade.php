@if($agent->isMobile())
<div class="bion-dropdown-container mt-5">
    <div class="d-flex flex-row ml-3 mr-3 align-items-center" type="button" data-toggle="collapse" data-target="#laporancontainer" aria-expanded="true" aria-controls="laporancontainer">
        <span class="text-uppercase mr-auto">Laporan</span>
        <i class="fas fa-chevron-down" id="collapsearrow"></i>
    </div>
</div>
@endif
<div class="row {{ ($agent->isMobile() ? "collapse" : "") }}" id="laporancontainer">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Laporan Penjualan Harian | {{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="d-flex flex-row mt-3">
                                <a type="button" class="btn btn-outline-primary text-uppercase rounded-pill mr-2" href="/{{ $cabang }}/api/data/penjualan/lihat/pdf/{{ date("Y-m-d") }}/{{ date("Y-m-d") }}" id="lihat-lap-btn" data-for="penjualan" target="_blank">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-address-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Laporan Arus Kas Harian | {{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="d-flex flex-row mt-3">
                                <a type="button" class="btn btn-outline-primary text-uppercase rounded-pill mr-2" href="/{{ $cabang }}/api/data/kas/lihat/pdf/{{ date("Y-m-d") }}/{{ date("Y-m-d") }}" id="lihat-lap-btn" data-for="kas" target="_blank">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Laporan Keuangan Harian | {{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="d-flex flex-row mt-3">
                                <a type="button" class="btn btn-outline-primary text-uppercase rounded-pill mr-2" href="/{{ $cabang }}/api/data/keuangan/lihat/pdf/{{ date("Y-m-d") }}/{{ date("Y-m-d") }}" id="lihat-lap-btn" data-for="keuangan" target="_blank">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lihat-laporan" tabindex="-1" aria-labelledby="popup-lihat-laporan-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="popup-lihat-laporan-label">Laporan Penjualan Harian | {{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="laporanTable" class="table text-center table-bordered table-hover table-responsive-sm" style="width:100%">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-item-container">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="download-laporan" tabindex="-1" aria-labelledby="popup-download-laporan-label" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width: 300px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="popup-download-laporan-label">Laporan Penjualan Harian | {{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-uppercase m-0">Ekspor Laporan</h5>
                <span>Silahkan pilih format:</span>
                <div class="d-flex mt-2">
                    <a type="button" class="btn btn-outline-danger w-100 mr-3" id="dl-btn" data-dl="pdf">
                        <i class="far fa-file-pdf"></i> PDF
                    </a>
                    <a type="button" class="btn btn-outline-success w-100" id="dl-btn" data-dl="excel">
                        <i class="far fa-file-excel"></i> EXCEL
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-item-container">Tutup</button>
            </div>
        </div>
    </div>
</div>