<template>
    <b-dropdown variant="black" right toggle-class="header-item">
        <template v-slot:button-content>
            <img class="mr-2" src="/assets/images/modules/warehouse.png" alt="Modules" height="16" />
            <span class="align-middle">Gudang Umum</span>
            <span class="mdi mdi-chevron-down"></span>
        </template>
        <b-dropdown-item href="javascript:void(0);">
            <span class="align-middle">Gudang Umum</span>
        </b-dropdown-item>
        <b-dropdown-item href="javascript:void(0);">
            <span class="align-middle">Gudang Greige</span>
        </b-dropdown-item>
        <b-dropdown-item href="javascript:void(0);">
            <span class="align-middle">Gudang Obat</span>
        </b-dropdown-item>
        <b-dropdown-item href="javascript:void(0);">
            <span class="align-middle">Gudang Barang Jadi</span>
        </b-dropdown-item>
    </b-dropdown>
</template>
<script>
export default {

}
</script>