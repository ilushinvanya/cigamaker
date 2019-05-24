<template>
    <div class="gallery_item"
         v-if="item"
         :class="{ pic_page : pic_page }">

        <img v-bind:src="'http://cigamaker.ru/images/'+item.image+'.svg'"
             v-bind:alt="escape(item.text)"
             @click="hover = !hover">
        <div class="info" v-show="hover">


            <router-link :to="{path: '/gallery/' + item.id_users}"
                         class="btn"><i class="fas fa-user"></i> {{ item.id_users }}</router-link>



            <router-link :to="{path: '/pic/' + item.picId}"
                         v-if="$route.path.indexOf('pic') == -1"
                         class="btn"><i class="far fa-image"></i></router-link>


                <button class="btn like_btn"
                        v-bind:class="{ active : checkLike(item.likes)}"
                        v-on:click="like()"
                        v-bind="{ disabled : !user }"
                        role="button">{{ item.likes.length }}
                        <i class="fas fa-dollar-sign"></i></button>
                <button class="btn share_btn"
                        role="button"
                        v-on:click="share = !share">
                    <i class="fas fa-share-alt"></i>
                </button>




        </div>

        <div class="ya-share2"
             v-bind:class="{open : share}"
             :id="'share_'+item.picId"></div>

        <div class="close_share"
             v-if="share"
             @click="share = false">
            <i class="fas fa-times"></i>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['pic_data'],
        data () {
            return {
                item: false,
                print:false,
                hover: false,
                share:false
            }
        },
        computed:{
            user(){
                return this.$store.state.authUser;
            },
            pic_page(){
                if(this.$route.path.includes('pic')){
                    return this.$route.params.id;
                }
            }
        },
        created(){

        },
        mounted(){
            if(this.pic_page){
                this.fetchPic();
            }else{
                this.item = this.pic_data;
            }
        },
        methods: {
            fetchPic(){
                var self = this;
                var data = new FormData();
                data.append('method', 'show_content');
                data.append('picture', this.pic_page);


//                console.log(this.$http.options.root)

                this.$http.post("php/index.php", data).then(response => {
                    var data = response.body.data[0];
                    self.item = data;
                    self.hover = true;

//                    self.shareInit();
                }, response => {
                    // error callback
                });
            },
            shareInit(){
                var self = this;
                var shareDiv = document.getElementById('share_' + this.item.picId);

                var share2 = Ya.share2(shareDiv, {
                    content: {
                        url: `http://cigamaker.ru/pic/${self.item.picId}`,
                        title: self.escape(self.item.text),
                        description: "",
                        image: "@/assets/lindsay_lohan_17.jpg"
                    },
                    theme: {
                        services: 'telegram,vkontakte,facebook,odnoklassniki,whatsapp,twitter,moimir,gplus,blogger,delicious,digg,lj,tumblr,viber,skype',
                        counter: false,
                        lang: 'ru',
                        limit: 15,
                        size: 'm',
                        bare: false
                    }

                });

            },
            checkLike: function (likes) {
                if (this.user) {
                    var counter = 0;
                    for (var i = 0; i < likes.length; i++) {
                        if (likes[i].id_users == this.user) {
                            counter++;
                        }
                        if (counter) {
                            return true;
                        }
                    }
                }
            },
            like() {
                var self = this;
                if (self.user) {
                    var data = new FormData();
                    data.append('method', 'like');
                    data.append('user', self.user.id);
                    data.append('id', self.item.picId);

                    self.$http.post("php/index.php", data).then(response => {
                        if (response.body.success) {
                            self.item.likes = response.body.data;
                        }
                    }, response => {
                        // error callback
                    });


                }
            },
            escape(str){
                if(!str){
                    return false;
                }
                var newstr = str.replace(/\n/g, ' ');
                return newstr;
            },
            setPic(){
                this.hover = !this.hover;
            }
        },
        watch: {
            item(){
                this.$nextTick(function () {
                    this.shareInit();
                })

            }
        }
    }
</script>

<style lang="scss">
    @import '../assets/styles/media';
    .gallery_item {
        box-sizing: border-box;
        position: relative;
        width: 21%;
        margin: 0 1rem 1rem 0;
        @include mobile {
            width: 48%;
        }
        img {
            width: 100%;
            display: block;
            cursor: pointer;
        }
        .info {
            position: absolute;
            width: 100%;
            background: #ffffffd1;
            padding: 10px 0;
            display: flex;
            /*justify-content: center;*/
            /*align-items: center;*/
            /*flex-direction: column;*/
            z-index: 99;
            a {
                color:black;
                text-decoration: none;
            }
            .btns_container {
                margin-top: 10px;

            }
            .btn {
                font-size: 13px;
                border: 1px gray solid;
                border-left-width: 0;
                background: white;
                padding: 10px 0;
                border-radius: 0;
                line-height: 0;
                width: 100%;
                text-align: center;
                white-space: nowrap;
                &:first-child{
                    border-radius: 4px 0 0 4px;
                    border-left-width: 1px;
                }
                &:last-child{
                    border-radius: 0 4px 4px 0;
                }
            }
        }

        button {
            position: relative;
        }
        .ya-share2 {
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            visibility: hidden;
            opacity: 0;
            background: white;
            z-index: 9999;
            padding: 7px;
            border: 1px #cccccc solid;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            &.open {
                visibility: visible;
                opacity: 1;
            }
            ul {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                li {
                    margin: 10px;
                    span {
                        height: 50px;
                        width: 50px;
                    }
                }
            }
        }

        .ya-share2__list_direction_vertical > .ya-share2__item {
            text-align: left;
        }

        .close_share {
            z-index: 999999;
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 40px;
            padding: 20px;
            color: black;
        }


        &.pic_page {
            width: 18%;
            @include mobile {
                width: 62%;
            }
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 62px);
            justify-content: center;
            .info {
                position: relative;
            }
        }
    }
</style>