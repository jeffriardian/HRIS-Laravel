<template>
    <Container :title="$route.meta.title" :module="this.$tc('engineering')">
        <router-view/>
    </Container>
</template>
<script>
import Container from '../../components/layouts/container';
export default {
    components: {Container},
}
</script>