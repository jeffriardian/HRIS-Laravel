<template>
    <Layout>
        <PageHeader :textTitle="$route.meta.title" :items="items" />
        dashboard
    </Layout>
</template>
<script>
import Layout from '../../../../components/layouts/main';
import PageHeader from '../../../../components/elements/page-header';
export default {
    components: {Layout,PageHeader},
    data() {
        return {
            items: [
                {
                    text: this.$route.meta.title,
                    active: true
                }
            ]
        };
    }
}
</script>
