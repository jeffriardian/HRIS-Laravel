<template>
    <Main>
        <PageHeader :textTitle="title" :items="items" />
        <slot />
    </Main>
</template>
<script>
import Main from './main';
import PageHeader from '../elements/page-header';
export default {
    components: {Main,PageHeader},
    props: {
        title: {
            type: String,
            default: '',
        },
        module: {
            type: String,
            default: 'General',
        },
    },
    data() {
        return {
            items: [
                {
                    text: this.$t('dashboard'),
                    to: { name: 'dashboard' }
                },
                {
                    text: this.module,
                    disabled: true
                },
                {
                    text: this.title,
                    active: true
                }
            ]
        };
    }
}
</script>