<template>
    <section id="root"></section>
</template>
<script>

    export default {
        name: "Root",
        mounted(){
            if(localStorage.getItem('cigamaker')){ // был ли здесь пользователь?
                this.$router.push({path:'/gallery'}) // Был, переход в галери
            }else{
                this.$router.push({path:'/promo'}) // Не был, то на промо
            }
        }
    };
</script>