<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
?>
<ul class="navbar-nav bg-bion-gradient-primary sidebar sidebar-dark accordion {{ ($agent->isMobile() ? "toggled" : "") }}" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
            <i class="fas fa-cash-register img-fluid"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ $cabang }}</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ ($title === "Dashboard" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Toko
    </div>

    <li class="nav-item {{ ($title === "Penjualan" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/penjualan">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Penjualan</span></a>
    </li>

    <li class="nav-item {{ ($title === "Kas" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/kas">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Arus Kas</span></a>
    </li>

    <li class="nav-item {{ ($title === "Master Item" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/product">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Master Item</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Pembukuan
    </div>

    <li class="nav-item {{ ($title === "Laporan Piutang" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/piutang">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Piutang Konsumen</span></a>
    </li>
    
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Petunjuk
    </div>

    <li class="nav-item {{ ($title === "Dokumentasi" ? "active" : "") }}">
        <a class="nav-link" href="/{{ $cabang }}/dokumentasi">
            <i class="fas fa-book"></i>
            <span>Dokumentasi</span></a>
    </li> 

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>