<template>
    <section id="root">


    </section>
</template>
<script>

    export default {
        name: "Root",
        data () {
            return {
            }
        },
        created(){},
        mounted(){
            // Здесь логика для локал сторадже, путей и регистрации, а также проверки на access_token

//            console.log("Root mounted",this)

            if(localStorage.getItem('cigamaker')){ // был ли здесь пользователь?
                this.$router.push({path:'/gallery'}) // Был, переход в галери
            }else{
                this.$router.push({path:'/promo'}) // Не был, то на промо
            }



        },
        computed: {

        },
        components: {

        },
        methods: {

        },
        watch: {

        }
    };
</script>

<style lang="scss">
    @import '../assets/styles/media';

</style>