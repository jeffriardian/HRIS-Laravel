<template>
  <div class="card">
    <div class="card-header"></div>
    <div class="card-body">
      <div class="row">
        <router-link to="/human-resource/interrogation-reports" class="btn btn-light ml-2">
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t('back') }}
        </router-link>
      </div>
      <div class="row mt-3">
        <div class="col-md-4">
          {{ $t('incident category') }}:
          <br />
          {{ detail.incident_category.name }}
        </div>
        <div class="col-md-4">
          {{ $t('date of incident') }}:
          <br />
          {{ detail.date_of_incident }}
        </div>
        <div class="col-md-4">
          {{ $t('lost cost') }}:
          <br />
          {{ Intl.NumberFormat().format(detail.lost_cost) }}
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <!-- <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{$tc('witness', 0)}}</h5>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
              <tr v-for="(row, index) in detail.interrogation_report_actors" :key="index">
                <td
                  v-if="row.interrogation_report_actor_type_id == 1"
                  class="align-middle"
                >{{ row.employee.name }}</td>
              </tr>
            </table>
          </div>-->
          <ul class="list-group">
            <li class="list-group-item list-group-item-secondary d-flex justify-content-center">
              <b>{{ $tc('witness', 0) }}</b>
            </li>
            <li
              class="list-group-item"
              v-for="(row, index) in witnesses"
              :key="index"
            >{{ row.employee.name }}</li>
          </ul>
        </div>
        <div class="col-md-6">
          <!-- <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{$tc('suspect', 0)}}</h5>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
              <tr v-for="(row, index) in detail.interrogation_report_actors" :key="index">
                <td
                  v-if="row.interrogation_report_actor_type_id == 2"
                  class="align-middle"
                >{{ row.employee.name }}</td>
              </tr>
            </table>
          </div>-->
          <ul class="list-group">
            <li class="list-group-item list-group-item-secondary d-flex justify-content-center">
              <b>{{ $tc('suspect', 0) }}</b>
            </li>
            <li
              class="list-group-item"
              v-for="(row, index) in suspects"
              :key="index"
            >{{ row.employee.name }}</li>
          </ul>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{$t('description')}}</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- <h5 class="card-title">Special title treatment</h5> -->
              <p class="card-text">{{ detail.description }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-12 text-center">
                  <h5 class="card-title">{{$tc('image', 0)}}</h5>
                </div>
              </div>
            </div>
            <div class="card-body d-flex flex-wrap">
              <!-- <h5 class="card-title">Special title treatment</h5> -->
              <!-- <p class="card-text">{{ detail.description }}</p> -->
              <div v-for="(image, index) in detail.interrogation_report_images" :key="index">
                <img
                  :src="'/storage/images/interrogation-report/'+ image.image"
                  :width="180"
                  :height="180"
                  alt
                  class="mr-2 mb-3"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  created() {
    this.show({ id: this.$route.params.id, action: "detail" });
  },
  methods: {
    ...mapActions("interrogationReport", ["show", "CLEAR_DETAIL"]),
  },
  computed: {
    ...mapState("interrogationReport", {
      detail: (state) => state.detail,
    }),
    witnesses() {
      return this.detail.interrogation_report_actors.filter(
        (number) => number.interrogation_report_actor_type_id == 1
      );
    },
    suspects() {
      return this.detail.interrogation_report_actors.filter(
        (number) => number.interrogation_report_actor_type_id == 2
      );
    },
  },
  destroyed() {
    this.CLEAR_DETAIL();
  },
};
</script>