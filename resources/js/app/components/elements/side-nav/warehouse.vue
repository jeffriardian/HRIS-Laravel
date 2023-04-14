<template>
    <div>
        <li class="menu-title">Gudang umum</li>
        <li>
            <router-link to="/calendar" class="side-nav-link-ref">
                <i class="bx bx-map-pin"></i> <span>Warehouse Maps</span>
            </router-link>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
                <i class="bx bx-briefcase-alt-2"></i> <span>Transaction</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/" class="side-nav-link-ref">Permintaan Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Permintaan Jasa</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pinjam Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pengajuan Barang (PO)</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Penerimaan Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Stok Opname</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pembelian (PO)</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pembelian (Langsung)</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pengadaan Jasa (PO)</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Persetujuan</router-link></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
                <i class="bx bx-receipt"></i> <span>Laporan</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/" class="side-nav-link-ref">Stok Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Permintaan Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Permintaan Jasa</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Peminjaman Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pengajuan Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Penerimaan Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pembelian Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pembelian Barang Langsung</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Pengadaan Jasa (PO)</router-link></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
                <i class="bx bx-book-content"></i> <span>Master Data</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/" class="side-nav-link-ref">Barang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Satuan</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Merek</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Mesin</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Mata Uang</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Supplier</router-link></li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">Kategori</a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="javascript: void(0);">Barang</a></li>
                        <li><a href="javascript: void(0);">Jasa</a></li>
                        <li><a href="javascript: void(0);">Retur</a></li>
                        <li><a href="javascript: void(0);">Supplier</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </div>
</template>
<script>
export default {
    
}
</script>