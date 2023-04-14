<template>
    <div>
        <div>
        <h3>Form Asessment Details </h3>
            <div v-for="(form, index) in score" class="form-row">
                <div class="form-group col-md-4" :class="{ 'has-error': errors.name }">
                    <b-form-rating v-model="form.score_id" variant="warning" class="mb-2" inline no-border readonly></b-form-rating> 
                </div>
                <div class="form-group col-md-8" :class="{ 'has-error': errors.min_score }">
                    <textarea
                        class="form-control"
                        :class="{ 'border-danger': errors.description }"
                        v-model="form.description"
                    ></textarea>
                    <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    data: function() {
        return {
            score: [
                {score_id: 1, description: ''},
                {score_id: 2, description: ''},
                {score_id: 3, description: ''},
                {score_id: 4, description: ''},
                {score_id: 5, description: ''},
            ]
        }  
    },
    // created: function(){
    //     axios.get('symbols.json').then(response => this.score = response.data);
    // },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('recruitmentStep', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        // ratingData(){
        //     return this.score
        // },
        hasil: function(){
            this.score.forEach((data) =>{
                return this.ratingForm.push({score_id: this.score.score_id, description: this.score.description});
            });
        }
    },
    watch: {
        score: {
            handler:function(param){
                this.ratingForm = this.score;
                console.log('test',this.score);
            }
        }   
    },
    created() {
        // this.addParameter();
        // this.dataRating();
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('recruitmentStep', ['CLEAR_FORM']),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        // this.CLEAR_FORM();
    }
}
</script>