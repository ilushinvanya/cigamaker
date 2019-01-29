<template>
    <section id="gifs">
        <div class="wrapper">

            <Gallery :pictures="gifs_list" :indx="0"></Gallery>
            <Gallery :pictures="gifs_list" :indx="1"></Gallery>
            <Gallery :pictures="gifs_list" :indx="2"></Gallery>

            <div class="description">
                <h1>Минздрав предупреждал, что всё очень плохо</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout
                    called a jumbotron and three supporting pieces of content. Use it as a starting point to create
                    something
                    more unique.</p>

            </div>

            <Gallery :pictures="gifs_list" :indx="4"></Gallery>
            <Gallery :pictures="gifs_list" :indx="5"></Gallery>
            <Gallery :pictures="gifs_list" :indx="6"></Gallery>

        </div>
        <Autorize></Autorize>
    </section>
</template>
<script>
    import Autorize from '../components/Autorize.vue'
    import Gallery from '../components/gifimages.vue'

    export default {
        name: "Gifs",
        data() {
            return {
                gifs_list:[]
            }
        },
        created() {
            var query_options = {
                api_key: "aXo2abWlsmGgq9UU5Zm5dyJgdlcB27Fb",
                q: "smoking",
                limit:40,
                offset:0,
                rating:"G",
                lang:"en"
            };

            var string = JSON.stringify(query_options)

            string = string.replace(/{\"/gi, "?")

            string = string.replace(/\":\"/gi, "=")
            string = string.replace(/\":/gi, "=")

            string = string.replace(/\",\"/gi, "&")
            string = string.replace(/,\"/gi, "&")

            string = string.replace(/\"}/gi, "")


            this.$http.get(`https://api.giphy.com/v1/gifs/search${string}`).then(data => {
                this.gifs_list = data.body.data;
            })


        },
        mounted: function () {
        },
        computed: {

        },
        components: { Autorize, Gallery },
        methods: {

        },
        watch: {}
    };
</script>

<style lang="scss">
    @import "../assets/styles/media";

    #gifs {
        position: relative;
        z-index: 6;
        background: white;
            display: block;

        .wrapper {
            height: 70vh;

            .description {
                padding: 24px;

                h1 {
                    padding-top: 0;
                    font-family: 'Lora', serif;
                    font-size: 43px;
                    text-align: left;
                    @include mobile {
                        margin: 0;
                        font-size: 20px;
                    }
                }
                p {
                    width: 100%;
                }
            }
        }

    }


</style>