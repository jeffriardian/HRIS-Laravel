<template>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ textTitle }}</h4>
                <div class="page-title-right">
                    <b-breadcrumb :items="items" class="m-0"></b-breadcrumb>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components: {},
    props: {
        textTitle: {
            type: String,
            default: '',
        },
        items: {
            type: Array,
            default: () => {
                return []
            },
        },
    },
}
</script>