<template>
    <header>
        <div class="container">
            <div class="authed"
                 v-if="user.uid">
                <div class="user_wrapper">
                    <!--<img :src="user.photo_50">-->
                    <div class="text">
                        <router-link :to="{ path: '/gallery/'+user.id}">{{ user.first_name }} {{ user.last_name }}</router-link>
                    </div>
                </div>

                <div class="menu">
                    <span class="btn btn_gallery"
                          @click="toPath('/gallery')"
                          v-show="path.includes('create')">

                    </span>

                    <span class="btn btn_plus"
                          @click="toPath('/create')"
                          v-show="path.includes('gallery') || path.includes('pic')">
                    </span>
                </div>
            </div>

            <div class="autorize"
                 v-else>
                <a v-show="!user.uid"
                   :href="$store.getters.auth_url + this.$route.path"><i
                        class="fab fa-vk"></i> авторизируйтесь, чтобы сохранять</a>
            </div>

        </div>

    </header>
</template>
<script>
    export default {
        name: "Header",
        data() {
            return {
            }
        },
        created() {

        },
        mounted: function () {
        },
        computed: {
            path(){
                return this.$route.path;
            },
            user(){
                return this.$store.state.authUser;
            }
        },
        components: {
        },
        methods: {
            toPath(route_name){
                this.$router.push({path:route_name})
            }
        },
        watch: {

        }
    };
</script>

<style lang="scss">
    @import "../assets/styles/media";
    header {

        position: relative;
        background: #fedb04;
        top:0;
        z-index: 9;
        width: 100%;

        .container {
            padding: 10px 0;
            .autorize {
                display: flex;
                justify-content: center;
                align-items: center;


                a {
                    border: 1px $blue solid;
                    color: $blue;
                    background: white;
                    padding: 8px 17px;
                    border-radius: 23px;
                    text-decoration: none;
                    font-size: 14px;
                    text-align: center;

                }


            }
            .authed {
                width: 96%;
                margin: 0 auto;
                display: flex;
                justify-content: center;
                align-items: center;
                .user_wrapper {
                    display: flex;
                    align-items: center;
                    /*border: 1px black solid;*/
                    /*background: white;*/

                    /*img {*/
                        /*width: 40px;*/
                    /*}*/
                    .text {
                        padding: 0 10px;
                        a {
                            font-family: 'Lora', serif;
                            margin: 0;
                            font-size: 1.2em;
                            font-weight: bold;
                            text-decoration: none;
                            color: black;
                        }
                    }
                }
                .menu {
                    position: absolute;
                    right: 0;
                    top: 0;
                    font-size: 28px;
                    color: #323232;
                    width: 40px;
                    background: white;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    .btn {
                        background-repeat: no-repeat;
                        background-position: center;
                        padding: 20px;
                        &.btn_gallery {
                            background-image: url(https://img.icons8.com/windows/32/000000/gallery.png);

                        }
                        &.btn_plus {
                            background-image: url(https://img.icons8.com/windows/32/000000/plus.png)
                        }
                    }
                }

            }

        }
    }


</style>