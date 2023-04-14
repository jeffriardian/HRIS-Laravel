export default {
    methods: {
        $can(permissionName) {
            let Permission = this.$store.state.utilities.auth.authenticated.permission
            if (typeof Permission != 'undefined') {
                return Permission.indexOf(permissionName) !== -1;
            }
        },
    },
};