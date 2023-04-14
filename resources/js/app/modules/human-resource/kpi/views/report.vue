<template>
  <div class="row">
    <div class="col-sm-4">
      <div class="search-box mr-2 mb-2 d-inline-block">
        <div class="position-relative">
          <input type="text" class="form-control" :placeholder="$t('search')" v-model="keyword" />
          <i class="bx bx-search-alt search-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-8">
      <div class="float-right">
        <b-dropdown right>
          <template v-slot:button-content>
            {{ $t('export') }}
            <i class="mdi mdi-chevron-down"></i>
          </template>
          <b-dropdown-item>
            <i class="mdi mdi-file-excel"></i> Excel
          </b-dropdown-item>
          <b-dropdown-item>
            <i class="mdi mdi-file-pdf"></i> PDF
          </b-dropdown-item>
        </b-dropdown>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      keyword: "",
    };
  },
};
</script>