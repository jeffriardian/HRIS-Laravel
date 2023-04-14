<template v-if="collection">
    <!-- https://forum.vuejs.org/t/vue-js-multi-level-bullet-list/44170/2 -->
    <ul :class="identifier ? `metismenu list-unstyled ${identifier}` : `sub-menu`" aria-expanded="false">
        <li v-for="(item, index) in collection" :key="index">
            <router-link :to="item.url ? item.url : `/`" class="side-nav-link-ref" :class="hasChildren(item) ? `has-arrow` : ``">
                <i class="bx bx-user-pin"></i> <span>{{ item.label }}</span>
            </router-link>
            <Navigation v-if="hasChildren(item)" :collection="item.children" />
        </li>
    </ul>
</template>
<script>
export default {
    name: "Navigation",
    props: {
        identifier: {
            type: String,
        },
        collection: {
            type: Array,
            required: true,
        }
    },
    methods: {
        hasChildren(item) {
            return (Array.isArray(item.children) && item.children.length);
        }
    },
    created(){
        console.log(this.identifier)
    }
}
</script>