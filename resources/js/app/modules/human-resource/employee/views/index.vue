<template>
  <div class="card">
    <div class="card-body">
      <!-- Card Header -->
      <div class="row mb-2">
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
            <button type="button" @click="migrationData()" class="btn btn-sm btn-info-rgba">
              <i class="feather icon-help-circle"></i>Migration
            </button>
            <b-dropdown>
              <template v-slot:button-content>
                {{ $t('export') }}
                <i class="mdi mdi-chevron-down"></i>
              </template>
              <b-dropdown-item @click="exportFile('excel')">
                <i class="mdi mdi-file-excel"></i> Excel
              </b-dropdown-item>
              <b-dropdown-item @click="exportFile('pdf')">
                <i class="mdi mdi-file-pdf"></i> PDF
              </b-dropdown-item>
            </b-dropdown>
            <router-link :to="{ name: 'employees.add' }" class="btn btn-success ml-2">
              <i class="mdi mdi-plus mr-1"></i>
              {{ $tc('create') }}
            </router-link>
            <!--<button type="button" @click="startTour()" class="btn btn-sm btn-info-rgba"><i class="feather icon-help-circle"></i></button>-->
          </div>
        </div>
      </div>
      <!-- End Card Header -->
      <b-table
        :sort-by.sync="tableParams.order_column"
        :sort-desc.sync="tableParams.order_direction"
        responsive="sm"
        thead-class="thead-light"
        small
        hover
        bordered
        :items="collection.data"
        :fields="fields"
        show-empty
      >
        <template v-slot:head()="data">{{ $tc(data.label) }}</template>
        <template
          v-slot:cell(index)="row"
        >{{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}</template>
        <template v-slot:cell(gender)="row">
          <span v-if="row.item.gender == 1">{{ $t('man') }}</span>
          <span v-else>{{ $t('woman') }}</span>
        </template>
        <template v-slot:cell(is_active)="row">
          <span
            class="badge badge-pill badge-soft-success font-size-12"
            v-if="row.item.is_active == 1"
          >{{ $t('active') }}</span>
          <span class="badge badge-pill badge-soft-danger font-size-12" v-else>{{ $t('inactive') }}</span>
        </template>
        <template v-slot:cell(photo)="row">
          <img
            :src="'/storage/images/employee-photos/' + row.item.photo"
            :width="80"
            :alt="row.item.name"
            v-if="row.item.photo != null"
          />
        </template>
        <template v-slot:cell(actions)="row">
          <router-link
            :to="{ name: 'employees.edit', params: {id: row.item.id} }"
            class="text-primary"
            v-b-tooltip.hover
            :title="$tc('update')"
          >
            <i class="mdi mdi-pencil-box-outline font-size-16"></i>
          </router-link>
          <a
            href="javascript:void(0)"
            @click="remove(row.item)"
            class="text-danger"
            v-b-tooltip.hover
            :title="$tc('delete')"
          >
            <i class="mdi mdi-trash-can-outline font-size-16"></i>
          </a>
        </template>
      </b-table>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <div>
        <b-form-select v-model="tableParams.per_page" :options="tableParams.pageOptions"></b-form-select>
      </div>
      <div>
        <p
          v-if="collection.data"
        >{{ $t('table summary', { from: collection.meta.from, to: collection.meta.to, total: collection.meta.total}) }}</p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          class="v-step-4"
          :total-rows="collection.meta.total"
          :per-page="collection.meta.per_page"
          v-if="collection.data && collection.data.length > 0"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  created() {
    this.load(this.tableParams);
  },
  data() {
    return {
      fields: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align: center; width: 35px;",
          tdClass: "text-center",
        },
        { key: "nik", label: "nik", thStyle: "width: 50px;" },
        {
          key: "department",
          label: "department",
          sortable: true,
          thStyle: "width: 180px;",
        },
        { key: "name", label: "name", sortable: true },
        {
          key: "gender",
          label: "gender",
          thStyle: "text-align: center; width: 150px;",
          tdClass: "text-center",
        },
        {
          key: "is_active",
          label: "status",
          thStyle: "text-align: center; width: 120px;",
          tdClass: "text-center",
        },
        {
          key: "photo",
          label: "photo",
          thStyle: "text-align: center; width: 120px;",
          tdClass: "text-center",
        },
        {
          key: "actions",
          label: "action",
          thStyle: "text-align: center; width: 80px;",
          tdClass: "text-center",
        },
      ],
      keyword: "",
    };
  },
  computed: {
    ...mapState("employee", {
      collection: (state) => state.collection,
    }),
    ...mapState("notification", {
      notify: (state) => state.notification,
    }),
    tableParams: {
      get() {
        //MENGAMBIL VALUE PAGE DARI VUEX MODULE
        return this.$store.state.humanResource.employee.tableParams;
      },
      set(params) {
        this.$store.commit("humanResource/employee/SET_TABLE_PARAMS", params);
      },
    },
  },
  watch: {
    tableParams: {
      handler: function (params) {
        let requestParams = {
          keyword: this.keyword,
          page: params.page,
          per_page: params.per_page,
          order_column: params.order_column,
          order_direction: params.order_direction,
        };
        this.load(requestParams);
      },
      deep: true,
    },
    keyword() {
      let requestParams = {
        keyword: this.keyword,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction,
      };
      this.load(requestParams);
    },
    notify() {
      this.load(this.tableParams);
    },
  },
  methods: {
    //MENGAMBIL FUNGSI DARI VUEX MODULE
    ...mapActions("employee", ["load", "destroy", "migration"]),
    remove(item) {
      this.$confirm(
        this.$t("delete confirmation", {
          item: item.name,
          module: this.$tc("employee"),
        }),
        this.$t("warning"),
        {
          confirmButtonText: this.$t("ok"),
          cancelButtonText: this.$t("cancel"),
          type: "warning",
        }
      )
        .then(() => {
          this.destroy(item.id);
          this.$message({
            type: "success",
            message: this.$t("delete successfully"),
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: this.$t("delete canceled"),
          });
        });
    },
    exportFile(type) {
      window.open(
        `/api/human-resource/employee-export-${type}?api_token=${this.$store.state.token}`
      );
    },
    startTour() {
      this.$tours["employee-tour"].start();
    },
    migrationData() {
      this.$confirm(
        this.$t("Migration Confirmation?", {
          module: this.$tc("Migration Data Employee"),
        }),
        this.$t("Confirmation"),
        {
          confirmButtonText: this.$t("ok"),
          cancelButtonText: this.$t("cancel"),
          type: "warning",
        }
      )
        .then(() => {
          console.log("success");
          this.migration();
          this.$message({
            type: "success",
            // message: this.$t('migration successfully')
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: this.$t("migration canceled"),
          });
        });
    },
  },
};
</script>
