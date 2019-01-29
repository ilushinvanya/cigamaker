<template>
    <section>



        <Header-app v-show="showHeader"></Header-app>

        <!--<transition name="slide-right">-->
            <router-view/>
        <!--</transition>-->



        <!--<footer>-->
            <!--<div class="container">-->
                <!--<p>© 2018 Компани, Инк.</p>-->
                <!--<a href="https://vk.com/public163288183" target="_blank">vk</a>-->
            <!--</div>-->
        <!--</footer>-->
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
        created(){
        },
        mounted: function () {

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
        methods: {

        },
        watch: {
            '$route' (to, from) {
//                const toDepth = to.path.split('/').length;
//                const fromDepth = from.path.split('/').length;
//                this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
            }
        }
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
        /*opacity: 0;*/
    }
</style>