<template>
    <section id="promo">

        <div class="lindsay">
            <div class="inner_text">
                <h1>СiшкаМейкер</h1>
                <p class="content"><b>"Надпись о вреде как искусство" ©</b></p>
                <p class="content">Интернет сервис предназначен чтобы создавать, на тему предупреждений о вреде курения на пачках сигарет</p>
            </div>
        </div>

        <Autorize></Autorize>


        <!--<div class="overflow"></div>-->
    </section>
</template>
<script>
    import Autorize from '../components/Autorize.vue';
    export default {
        name: "App",
        data () {
            return {
            }
        },
        created(){},
        mounted: () => {
            localStorage.setItem('cigamaker', true)
        },
        computed: {

        },
        components: {
            Autorize
        },
        methods: {

        },
        watch: {

        }
    };
</script>

<style lang="scss">
    @import '../assets/styles/media';
    #promo {
        height: 100vh;
        position: relative;
        z-index: 5;
        @include mobile {
            /*padding-top: 1px;*/
        }

        .lindsay{
            height: 70vh;
            background-image: url(../assets/lindsay_lohan_17.jpg);
            background-color: #ffe9fd;
            background-size: cover;
            background-position: center;

            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
            .inner_text{
                h1 {
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    font-size: 55px;

                    background-color: #fedb04;
                    padding: 10px;
                    display: inline-block;
                    margin: 0;

                    @include mobile {
                        font-size: 32px;
                        margin-top: 40px;
                        text-align: center;
                        width: 100%;
                        display: block;
                    }
                }
                .content {
                    width: 40%;
                    background-color: #fedb04;
                    padding: 10px;
                    margin: 10px 0;
                    @include mobile {
                        width: 100%;
                        margin-bottom: 0;
                    }
                }
            }
        }

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
            }
        }


        .overflow {
            position: absolute;
            z-index: 99999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 192, 203, 0.69);
        }

    }
</style>