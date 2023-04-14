<template>
    <Layout>
        <PageHeader :textTitle="this.$route.meta.title" :items="items" />
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div class="text-center mb-5">
                    <img src="/assets/images/internal-server.svg" alt class="img-fluid" />
                    <h4 class="text-uppercase">{{ $t('error message') }}</h4>
                    <div class="mt-5 text-center">
                        <router-link class="btn btn-primary" to="/">
                            <i class="bx bx-home-circle"></i> Back to Dashboard
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
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
                    text: this.$t('dashboard'),
                    to: { name: 'dashboard' }
                },
                {
                    text: this.$route.meta.title,
                    active: true
                }
            ]
        };
    }
}
</script>