<template>
    <section>
        <Header-app v-show="showHeader"></Header-app>
        <router-view/>
    </section>
</template>
<script>
    import Headerapp from './components/Header.vue';

    export default {
        name: "App",
        data () {
            return {
                transitionName: false
            }
        },
        mounted() {
            var access_token = localStorage.getItem('cigamaker_token');
            var user_id = localStorage.getItem('cigamaker_user');

            if(access_token && user_id){
                this.$store.dispatch('get_data_from_vk');
            }
        },
        computed: {
            showHeader(){
                if(this.$route.path == '/promo' || this.$route.path == '/gifs' || this.$route.path == '/'){
                    return false;
                }
                return true;
            }
        },
        components: {
            "Header-app":Headerapp
        },
    };
</script>

<style lang="scss">
    @import "assets/styles/media";
    .slide-right-enter-active {
        transition: all .8s ease;
    }
    .slide-right-leave-active {
        transition: all .8s ease;
    }
    .slide-right-enter, .slide-leave-to {
        transform: translateX(320px);
    }
</style>