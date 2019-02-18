<template>
    <div class="gallery" @scroll="elemInViewport()">

        <div class="loading" v-show="loading"></div>


        <h2 v-if="profile">Галерея Пользователя {{ profile }}</h2>
        <span class="show_all"
              v-if="profile"
              @click="resetProfile()">показать Всех</span>

        <h2 v-if="!profile">Галерея всех пользователей</h2>

        <div class="gallery_list">
            <kartina v-for="pic in pics"
                     :pic_data="pic"
                     :key="pic.id"></kartina>
        </div>

        <div class="btn_wrap"
             v-if="overPage">
            <div class="btn"
                 id="submit"
                 v-on:click="incrementPage()">Показать ещё
            </div>
        </div>
    </div>
</template>

<script>
    import kartina from '../components/kartina.vue';

    export default {

        data() {
            return {
                overPage: true,
                page: 0,
                pics: [],
                loading: false
            }
        },
        computed: {
            profile() {
                return this.$route.params.id;
            },
            cols() {
                if (this.$store.getters.mobile) {
                    return 2;
                } else {
                    return 5;
                }
            },
            user() {
                return this.$store.state.authUser;
            }
        },
        mounted: function () {
            this.load();
        },
        components: {
            'kartina': kartina
        },
        methods: {
            elemInViewport(full) {
                var element = document.getElementById('submit');
                if(!element) return false;

                var box = element.getBoundingClientRect();
                var top = box.top;
                var left = box.left;
                var bottom = box.bottom;
                var right = box.right;
                var width = document.documentElement.clientWidth;
                var height = document.documentElement.clientHeight;
                var maxWidth = 0;
                var maxHeight = 0;
//                if (full) {
//                    maxWidth = right - left;
//                    maxHeight = bottom - top
//                }
                var res = Math.min(height, bottom) - Math.max(0, top) >= maxHeight && Math.min(width, right) - Math.max(0, left) >= maxWidth;

                if(res){
                    this.incrementPage();
                }

            },
            load() {

                if(this.loading){
                    return false;
                }

                var self = this;
                self.loading = true;
                var data = new FormData();
                data.append('method', 'show_content');
                data.append('page', this.page);

                if (this.profile) {
                    data.append('profile', this.profile);
                }


                this.$http.post("php/index.php", data).then(response => {

                    var data = response.body.data;

                    if (self.page > 0) { // page не первый, то делаем конкат
                        self.pics = self.pics.concat(data);
                    } else { // если это первая страница, то просто обновляем данные
                        self.pics = [];
                        self.$nextTick(()=>{
                            self.pics = data;

                        })
                    }
                    if (data.length < 10) { // а это, если ответ содержит меньше 10 объектов, то оверПейдж убираем
                        self.overPage = false;
                    } else {
                        self.overPage = true;
                    }

                    self.loading = false;


                    console.log(self.pics)
                }, response => {
                    // error callback
                });
            },
            resetProfile() {
                this.page = 0;
                this.$router.push({path: '/gallery'});
            },
            incrementPage() {
                this.page++;
                this.load();
            }
        },
        watch: {
            profile() {
                this.page = 0;
            },
            $route(to, from) {
                this.page = 0;
                this.load()


            }
        }
    }
</script>

<style lang="scss">
    @import '../assets/styles/media';

    .gallery {
        padding: 20px;
        height: 90vh;
        overflow: hidden;
        overflow-y: scroll;

        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9;
            opacity: 0.8;
        }

        h2 {
            text-align: center;
            font-family: 'Lora', serif;
            margin: 0;
            font-size: 1.2em;
        }
        .show_all {

        }
        .gallery_list {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 30px;
        }
        .btn_wrap {
            text-align: center;
            margin: 20px 0;
            .btn {
                background: #ffffff;
                border: 2px black solid;
                display: inline-block;
                padding: 9px 14px;
                border-radius: 3px;
            }
        }
        .show_all {
            cursor: pointer;
            /*border-bottom: 1px dotted #878787;*/
            display: block;
            text-align: center;
        }
    }

</style>