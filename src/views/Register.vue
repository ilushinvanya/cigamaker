<template>
    <section id="register">
        <!--<h1>Register</h1>-->
    </section>
</template>
<script>
    export default {
        name: "register",
        data() {
            return {}
        },
        created() {},
        mounted() {
            var self = this;
            if (this.$route.hash.length > 0) {

                var url = this.$route.hash.substring(1);


                var paramsObj = {};
                var vars = url.split('&');
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split('=');
                    paramsObj[pair[0]] = pair[1];
                }

//                console.log("paramsObj",paramsObj)

                if (paramsObj.access_token) {
                    localStorage.setItem('cigamaker_token', paramsObj.access_token);
                    localStorage.setItem('cigamaker_user', paramsObj.user_id);

                    this.$store.dispatch('get_data_from_vk');

                    this.$router.push({path:paramsObj.state})
                    history.pushState("", document.title, window.location.pathname + window.location.search);

                }else{
                    this.$router.push({path:"/"})
                }


            };
        },
        computed: {},
        components: {},
        methods: {},
        watch: {}
    };
</script>

<style lang="scss">
    @import "../assets/styles/media";
</style>