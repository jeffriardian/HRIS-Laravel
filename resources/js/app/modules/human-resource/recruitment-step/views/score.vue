<template>
    <div>
        <div class="card m-b-30">
            <div class="card-header">
                <div class="col-9">
                    <h5 class="card-title">
                        {{ $tc("Form Asessment Details", 2) }}
                    </h5>
                </div>
            </div>
            <hr />
            <div
                v-for="(form,
                index) in parameter.recruitment_step_parameter_scores"
                class="form-row"
            >
                <div class="form-group col-md-4">
                    <b-form-rating
                        v-model="form.score_id"
                        variant="warning"
                        class="mb-2"
                        inline
                        no-border
                        readonly
                    ></b-form-rating>
                </div>
                <div class="form-group col-md-8">
                    <textarea
                        class="form-control"
                        v-model="form.description"
                    ></textarea>
                    <p
                        class="text-danger"
                        v-if="
                            errors[
                                `parameters.${index}.recruitment_step_parameter_scores.${index}.description`
                            ]
                        "
                    >
                        {{
                            errors[
                                `parameters.${index}.recruitment_step_parameter_scores.${index}.description`
                            ][0]
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    props: ["modelId", "parameter"],
    created() {
        console.log(this.modelId);
        if (this.modelId) {
            if (this.parameter.recruitment_step_parameter_scores.length == 0) {
                this.parameter.recruitment_step_parameter_scores.push(
                    { score_id: 1, description: "" },
                    { score_id: 2, description: "" },
                    { score_id: 3, description: "" },
                    { score_id: 4, description: "" },
                    { score_id: 5, description: "" }
                );
            }
        } else {
            this.parameter.recruitment_step_parameter_scores = [
                { score_id: 1, description: "" },
                { score_id: 2, description: "" },
                { score_id: 3, description: "" },
                { score_id: 4, description: "" },
                { score_id: 5, description: "" }
            ];
        }
    },
    computed: {
        ...mapState(["errors"])
    }
};
</script>
