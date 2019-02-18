<template>
    <div class="images">
        <a v-for="(item,index) in shuffle(pictures)"
           :href="item.url"
           target="_blank"
           v-if="index < 5"
           :key="index">
           <img :src="item.images.fixed_height_small.url"/>

        </a>
    </div>
</template>
<script>
    export default {
        name: "Gifs_gallery",
        props: ['pictures', 'indx'],
        methods: {
            shuffle(array=[]) {
                var currentIndex = array.length, temporaryValue, randomIndex;

                // While there remain elements to shuffle...
                while (0 !== currentIndex) {

                    // Pick a remaining element...
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;

                    // And swap it with the current element.
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            }
        },
    }
</script>

<style lang="scss">
    .images {
        display: flex;
        overflow: hidden;
        a {
            img {
                height: 50px;
                display: block;
            }
        }
    }


</style>