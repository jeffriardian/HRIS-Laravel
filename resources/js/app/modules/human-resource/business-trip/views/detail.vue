<template>
  <div class="card">
    <div class="card-header">
      <!-- <h5 class="card-title">{{ $tc('detail') }} {{ $tc('Business Trip') }}</h5> -->
    </div>
    <div class="card-body">
      <div class="row">
        <router-link to="/human-resource/business-trips" class="btn btn-light ml-2">
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t('back') }}
        </router-link>
      </div>
      <div class="row mt-3">
        <div class="col-md-3">
          {{ $t('name') }}:
          <br />
          {{ detail.employee.name }}
        </div>
        <div class="col-md-3">
          {{ $t('destination') }}:
          <br />
          {{ detail.destination }}
        </div>
        <div class="col-md-3">
          {{ $t('departure date') }}:
          <br />
          {{ detail.departure_date }}
        </div>
        <div class="col-md-3">
          {{ $t('back date') }}:
          <br />
          {{ detail.back_date }}
        </div>
      </div>
      <div class="row mt-3">
        <div
          class="col-md-12"
        >Reimburse Type : {{ detail.reimburse != null ? detail.reimburse.reimburse_type : "" }}</div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          {{ $t('purpose') }} :
          <br />
          {{ detail.purpose }}
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{ $tc('transportation',2) }}</h5>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
              <thead>
                <tr>
                  <th width="25%">{{ $t('transportation') }}</th>
                  <th>{{ $t('cost') }}</th>
                  <th>{{ $tc('image', 0) }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, index) in detail.transportations"
                  :key="index"
                  class="justify-content-between align-items-center"
                >
                  <td
                    class="align-middle"
                  >{{row.transportation != null ? row.transportation.name : ""}}</td>
                  <td class="align-middle">{{ Intl.NumberFormat().format(row.cost) }}</td>
                  <td>
                    <div v-if="detail.images.length > 0" class="d-flex flex-wrap">
                      <div v-for="(image, index) in detail.images" :key="index">
                        <img
                          v-if="image.payment_slip_type == 1 && image.payment_slip_id == row.transportation_id"
                          :src="'/storage/images/business-trip/'+ image.image"
                          :width="80"
                          alt
                          class="mr-2"
                        />
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{ $tc('allowance',2) }}</h5>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
              <thead>
                <tr>
                  <th width="25%">{{ $t('allowance') }}</th>
                  <th>{{ $t('cost') }}</th>
                  <th>{{ $tc('image', 0) }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, index) in detail.parameters"
                  :key="index"
                  class="justify-content-between align-items-center"
                >
                  <td
                    class="align-middle"
                  >{{ row.receipt_parameter != null ? row.receipt_parameter.name : "" }}</td>
                  <td class="align-middle">{{ Intl.NumberFormat().format(row.cost) }}</td>
                  <td>
                    <div v-if="detail.images.length > 0" class="d-flex flex-wrap">
                      <div v-for="(image, index) in detail.images" :key="index">
                        <img
                          v-if="image.payment_slip_type == 0 && image.payment_slip_id == row.receipt_parameter_id"
                          :src="'/storage/images/business-trip/'+ image.image"
                          :width="80"
                          alt
                          class="mr-2"
                        />
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          Result:
          <br />
          {{ detail.business_trip_result != 'null' ? detail.business_trip_result : '' }}
        </div>
      </div>
    </div>
    <div class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center"></div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  created() {
    //SEBELUM COMPONENT DI-RENDER KITA MELAKUKAN REQUEST KESERVER
    //BERDASARKAN ID YANG ADA DI URL
    this.show({ id: this.$route.params.id, action: "detail" });
  },
  methods: {
    ...mapActions("businessTrip", ["show"]),
    ...mapMutations("businessTrip", ["CLEAR_DETAIL"])
  },
  computed: {
    ...mapState("businessTrip", {
      detail: state => state.detail //MENGAMBIL STATE DATA
    })
  },
  destroyed() {
    this.CLEAR_DETAIL();
  }
};
</script>