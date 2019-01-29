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
        created() {
//            fetch("https://api.giphy.com/v1/gifs/search?api_key=aXo2abWlsmGgq9UU5Zm5dyJgdlcB27Fb&q=smoke&limit=25&offset=0&rating=G&lang=en").then(data => {
//                console.log(data)
//            })
        },
        mounted: function () {
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
                    self.$http.jsonp("https://api.vk.com/method/users.get?user_ids=" + paramsObj.user_id + "&fields=bdate,photo_50&v=5.74&access_token=" + paramsObj.access_token).then(response => {

                        this.$store.commit('setUser', response.body.response[0]);

                        this.$router.push({path:paramsObj.state})

                        history.pushState("", document.title, window.location.pathname + window.location.search);
                    }, response => {
                        // error callback
                    });
                }


            };
        },
        computed: {

        },
        components: {

        },
        methods: {},
        watch: {}
    };
</script>

<style lang="scss">
    @import "../assets/styles/media";
</style>