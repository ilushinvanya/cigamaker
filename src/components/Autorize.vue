<template>
    <div class="autorize">
        <button class="enter" @click="enter()" v-if="!user">Войти</button>
        <button class="next" @click="next()">Далее</button>
    </div>
</template>
<script>

    export default {
        name: "autorize",
        data () {
            return {
            }
        },
        computed:{
            user(){
                return this.$store.state.authUser;
            }
        },
        methods: {
            next(){
                if(this.$route.path == '/promo'){
                    this.$router.push({path:'/gifs'})
                }else if(this.$route.path == '/gifs'){
                    this.$router.push({path:'/gallery'})
                }
            },
            enter(){
                window.location.href = this.$store.getters.auth_url + this.$route.path;
            }

        },
        watch: {

        }
    };
</script>

<style lang="scss" scoped>
    @import '../assets/styles/media';
    .autorize {
            height: 30vh;
            /*background: deeppink;*/
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            button {
                background: white;
                padding: 17px 35px;
                border-radius: 43px;
                font-size: 22px;
                margin: 10px 0;

            }
            .enter {
                border: 3px #fedb04 solid;
            }
            .next {
                border: 1px black solid;
                &:focus,
                &:active {
                    background: black;
                    color: white;
                }
            }
        }


</style>