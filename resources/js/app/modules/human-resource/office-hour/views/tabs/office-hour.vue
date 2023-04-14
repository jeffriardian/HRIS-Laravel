<template>
  <div>
    <div class="card-body">
      <!-- Card Header -->
      <div class="row mb-2">
        <div class="col-sm-4">
          <div class="search-box mr-2 mb-2 d-inline-block">
            <div class="position-relative">
              <input
                type="text"
                class="form-control"
                :placeholder="$t('search')"
                v-model="keyword"
              />
              <i class="bx bx-search-alt search-icon"></i>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="float-right">
            <b-dropdown>
              <template v-slot:button-content>
                {{ $t("export") }}
                <i class="mdi mdi-chevron-down"></i>
              </template>
              <b-dropdown-item @click="exportFile('excel')">
                <i class="mdi mdi-file-excel"></i> Excel
              </b-dropdown-item>
              <b-dropdown-item @click="exportFile('pdf')">
                <i class="mdi mdi-file-pdf"></i> PDF
              </b-dropdown-item>
            </b-dropdown>
            <b-button v-b-modal="'form-modal'" class="btn btn-success ml-2">
              <i class="mdi mdi-plus mr-1"></i>
              {{ $tc("create") }}
            </b-button>
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
        <template v-slot:head()="data">{{ $t(data.label) }}</template>
        <template v-slot:cell(index)="row">{{
          tableParams.page !== 1
            ? row.index + 1 + tableParams.per_page * (tableParams.page - 1)
            : row.index + 1
        }}</template>
        <template v-slot:cell(weekday)="row"
          >{{ row.item.weekday_in }} - {{ row.item.weekday_out }}</template
        >
        <template v-slot:cell(weekend)="row"
          >{{ row.item.weekend_in }} - {{ row.item.weekend_out }}</template
        >
        <template v-slot:cell(is_active)="row">
          <span
            class="badge badge-pill badge-soft-success font-size-12"
            v-if="row.item.is_active == 1"
            >{{ $t("active") }}</span
          >
          <span
            class="badge badge-pill badge-soft-danger font-size-12"
            v-else
            >{{ $t("inactive") }}</span
          >
        </template>
        <template v-slot:cell(actions)="row">
          <a
            href="javascript:void(0)"
            @click="view(row.item.id)"
            class="text-primary"
            v-b-tooltip.hover
            :title="$tc('update')"
          >
            <i class="mdi mdi-pencil-box-outline font-size-16"></i>
          </a>
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
    <div
      class="card-footer bg-white d-flex justify-content-between align-items-center"
    >
      <div>
        <b-form-select
          v-model="tableParams.per_page"
          :options="tableParams.pageOptions"
        ></b-form-select>
      </div>
      <div>
        <p v-if="collection.data">
          {{
            $t("table summary", {
              from: collection.meta.from,
              to: collection.meta.to,
              total: collection.meta.total,
            })
          }}
        </p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          :total-rows="collection.meta.total"
          :per-page="collection.meta.per_page"
          v-if="collection.data && collection.data.length > 0"
        ></b-pagination>
      </div>
    </div>
    <b-modal
      id="form-modal"
      :title="$t('form module', { module: $tc('office hour') })"
      ref="modal"
    >
      <form v-on:submit.prevent="submit">
        <formComponent :modelId="modelId"></formComponent>
      </form>
      <template v-slot:modal-footer>
        <b-button class="btn btn-secondary ml-2" @click="hideModal()">
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t("cancel") }}
        </b-button>
        <button class="btn btn-primary" @click.prevent="submit">
          <b-spinner v-if="loadingProcess" small class="mr-2"></b-spinner>
          <i v-else class="far fa-save mr-2"></i>
          {{ $t("save") }}
        </button>
      </template>
    </b-modal>
  </div>
</template>
<script>
import { mapActions, mapMutations, mapState } from "vuex";
// import formComponent from "../form.vue";
import formComponent from "../form-v2.vue";
export default {
  data() {
    return {
      url: "office-hours",
      fields: [
        {
          key: "index",
          label: "#",
          tdClass: "text-center",
        },
        {
          key: "name",
          label: "name",
          sortable: true,
          tdClass: "text-center",
        },
        {
          key: "weekday",
          label: "weekday",
          sortable: true,
          tdClass: "text-center",
        },
        {
          key: "weekend",
          label: "weekend",
          sortable: true,
          tdClass: "text-center",
        },
        {
          key: "is_active",
          label: "status",
          tdClass: "text-center",
        },
        {
          key: "actions",
          label: "action",
          tdClass: "text-center",
        },
      ],
      keyword: "",
      modelId: null,
      loadingProcess: false,
    };
  },
  components: {
    formComponent,
  },
  computed: {
    ...mapState("officeHour", {
      collection: (state) => state.collection,
    }),
    ...mapState("notification", {
      notify: (state) => state.notification,
    }),
    ...mapState(["errors"]),
    tableParams: {
      get() {
        //MENGAMBIL VALUE PAGE DARI VUEX MODULE
        return this.$store.state.humanResource.officeHour.tableParams;
      },
      set(params) {
        this.$store.commit("humanResource/officeHour/SET_TABLE_PARAMS", params);
      },
    },
  },
  created() {
    this.load({ url: this.url, data: this.tableParams });
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
        this.load({ url: this.url, data: requestParams });
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
      this.load({ url: this.url, data: requestParams });
    },
    notify() {
      this.load({ url: this.url, data: this.tableParams });
    },
    errors() {
      if (typeof this.errors == "object") {
        this.loadingProcess = false;
      }
    },
  },
  mounted() {
    this.$root.$on("bv::modal::hidden", (bvEvent, modalId) => {
      this.modelId = null;
    });
  },
  methods: {
    //MENGAMBIL FUNGSI DARI VUEX MODULE
    ...mapActions("officeHour", ["load", "show", "store", "update", "destroy"]),
    hideModal() {
      this.loadingProcess = false;
      this.load({ url: this.url, data: this.tableParams });
      this.$bvModal.hide("form-modal");
    },
    view(id) {
      this.show({ url: this.url, id: id });
      this.modelId = id;
      this.$bvModal.show("form-modal");
    },
    submit() {
      this.loadingProcess = true;
      if (!this.modelId) {
        this.store({ url: this.url }).then(() => {
          this.hideModal();
          this.$message({
            type: "success",
            message: this.$t("present perfect", {
              object: this.$tc("office hour"),
              message: this.$tc("add", 2),
            }),
          });
          this.loadingProcess = false;
        });
      } else {
        this.update({ url: this.url, id: this.modelId }).then(() => {
          this.hideModal();
          this.$message({
            type: "success",
            message: this.$t("present perfect", {
              object: this.$tc("office hour"),
              message: this.$tc("update", 2),
            }),
          });
          this.loadingProcess = false;
        });
      }
    },
    remove(item) {
      this.$confirm(
        this.$t("delete confirmation", {
          item: item.name,
          module: this.$tc("office hour"),
        }),
        this.$t("warning"),
        {
          confirmButtonText: this.$t("ok"),
          cancelButtonText: this.$t("cancel"),
          type: "warning",
        }
      )
        .then(() => {
          this.destroy({ url: this.url, id: item.id });
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
  },
};
</script>
