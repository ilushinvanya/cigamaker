<template>
    <div class="fabric">

        <div class="fabric_wrapper">
            <canvas :width="width" :height="app_height" id="c"></canvas>
        </div>
        <div class="controls">
            <textarea class="form-control"
                  v-model="mess"
                  placeholder="Курение не убивает"></textarea>
            <input type="range"
               list="tickmarks"
               v-model="fsize"
               min="10"
               max="30"/>
            <datalist id="tickmarks">
            <option value="5" label="5"/>
            <option value="10" label="10"/>
            <option value="20" label="20"/>
            <option value="30" label="30"/>
        </datalist>
            <div class="btn-container">
                <div class="show_pacs"
                     v-if="show_pacs">
                    <ul>
                        <li v-for="pac in pacs">
                            <img :src="pac"
                                 @click="votePac(pac)"/>
                        </li>
                    </ul>
                </div>

                <div class="input-container input-group">
                    <button class="btn"
                            type="button"
                            v-on:click="show_pacs = !show_pacs">Пачки</button>
                    <input type="text"
                           class="form-control"
                           v-model="image_url"
                           placeholder="url картинки"
                           v-on:keyup.enter="addImage()"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default"
                                type="button"
                                v-on:click="addImage()">Адд пик</button>
                    </span>
                </div>

                <button type="button"
                id="save"
                class="btn save"
                v-on:click="save()"
                v-if="user"
                v-bind="{disabled: !enable}"><i class="fas fa-cloud"></i>
                Сохранить
                </button>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data () {
            return {
                app_height: 500,
                width: 184,
                height: 95,
                padding: 10,
                string: 'НЕ КУРЕНИЕ\nУБИВАЕТ',
                mess:'',
                fsize: 23,
                enable: true,
                image_url: '',
                pacs:["http://cigamaker.ru/img/pacs/parl_wo_brand.jpg", "http://cigamaker.ru/img/pacs/01.jpg", "http://cigamaker.ru/img/pacs/04.jpg", "http://cigamaker.ru/img/pacs/03.jpg"],
                show_pacs: false
            }
        },
        computed: {
            user: {
                get () {
                    return this.$store.state.authUser;
                }
            }
        },
        created(){

        },
        mounted(){
            this.init();
            if (sessionStorage.getItem("cigamaker_msg")) {
                this.mess = sessionStorage.getItem("cigamaker_msg");
            }

            if (sessionStorage.getItem("cigamaker_fontsize")) {
                this.fsize = sessionStorage.getItem("cigamaker_fontsize");
            }

            if (sessionStorage.getItem("cigamaker_image")) {
                this.image_url = sessionStorage.getItem("cigamaker_image");
//                this.addImage();
            }


        },
        methods: {
            votePac(pac){
                this.image_url = pac;
                this.addImage()
            },
            init(){
                var self = this;
                canvas = new fabric.Canvas('c',{selectable: false});
                textbox = new fabric.Text(self.string, {
                     id: 1,
                     left: self.width / 2,
                     top: (self.app_height/2 - self.height/2) + 20,
                     originX: 'center',
                     originY: 'top',
                     width: self.width - 40,
                     height: self.height - 40,
                     fontSize: self.fsize,
                     fontFamily: 'Arial',
                     fontWeight: 'bold',
                     textAlign: 'center',
                     hasControls: false,
                     selectable: false,
                     hoverCursor: "default"

                 });
                rectBlack = new fabric.Rect({
                    id: 2,
                    left: 0,
                    top: self.app_height/2 - self.height/2,
                    originX: 'left',
                    originY: 'top',
                    width: self.width,
                    height: self.height,
                    fill: 'rgba(0,0,0,1)',
                    hasControls: false,
                    selectable: false,
                    hoverCursor: "default"
                });
                rectWhite = new fabric.Rect({
                    id: 3,
                    left: 10,
                    top: (self.app_height/2 - self.height/2) + 10,
                    originX: 'left',
                    originY: 'top',
                    width: self.width - 20,
                    height: self.height - 20,
                    fill: 'rgba(255,255,255,1)',
                    hasControls: false,
                    selectable: false,
                    hoverCursor: "default"
                });

                canvas.add(rectBlack);
                canvas.add(rectWhite);
                canvas.add(textbox);

                canvas.requestRenderAll();
            },
            addImage(){
                var self = this;
                if(rectImage !== undefined) {
                    canvas.remove(rectImage);
                    rectImage = undefined;
                };

//                var data = new FormData();
//                data.append('method', 'url_pic');
//                data.append('url', self.image_url);
//                self.$http.post("./php/index.php", data).then(response => {
//                    console.log(response);
//                });


                var imgObj = new Image();
                imgObj.src = self.image_url;
                imgObj.onload = function () {

                    fabric.Image.fromURL(self.image_url, function (oImg) {
                        rectImage = oImg;
                        oImg.scaleToWidth(self.width).setOptions({
                            selectable: false,
                            hoverCursor: "default",
                            top: 0,
                            originY: 'top'
                        });
                        canvas.add(oImg);
                        self.reSize();
                        self.image_url = '';
                    }, {crossOrigin: 'Anonymous'});


                }


                imgObj.onerror = function(){
                    self.image_url = '';
                    alert('Что-то не загрузилось, попробуйте другую картинку из другого источника, спасибо, извините')
                }
            },
            save: function () {
                sessionStorage.clear();
                var self = this;
                if (self.user) {
                    var mess = textbox.get('text');
                    if (mess.length == 0 || mess == self.string || mess == ' ') {
                        alert('Введите сообщение');
                        return false;
                    }

                    var totalHeight = rectBlack.get('height');
                    var vueBox = {
                        x: 0,
                        width: self.width,
                        height: totalHeight
                    };

                    if(rectImage !== undefined){ // если есть изображение
                        vueBox.y = 0;
                    }else{ // если его нет то из центра считаем
                        vueBox.y = rectBlack.get('top');
                    }

                    self.enable = false;
                    var image = canvas.toSVG({
                        viewBox: vueBox,
                        width: self.width,
                        height: totalHeight
                    });


                    var data = new FormData();
                    data.append('method', 'save');
                    data.append('img', image);
                    data.append('user', self.user.id);
                    data.append('txt', textbox.get('text').toLowerCase());

//                    yaCounter47987582.reachGoal('add');
                    self.$http.post("php/index.php", data).then(response => {
                        if (response.body.success == true) {

                            // Сохранилось
                            // Надо открыть в отдельном окне



                            alert(response.body.msg);
                            // Вывести окно о завершении

                            self.$router.push({path:'pic/' + response.body.id})

                            self.mess = '';
                            self.image_url = '';
                            if(rectImage !== undefined) {
                                canvas.remove(rectImage);
                                rectImage = undefined;
                            }

                            setTimeout(function () {
                                self.enable = true;
                            }, 10000)
                        } else {
                            // Сообщение об ошибке
                            alert(response.body.data);

                            // А это если повтор, то открыть что пришло
                            if (response.body.origin) {

                                self.enable = true;
                                self.$router.push({path:'pic/' + response.body.origin})
                            }
                        }
                    }, response => {
                        // error callback
                    });


                }
            },
            onTextExited: function (value) {
                textbox.set({text: value.toUpperCase()});
                sessionStorage.setItem("cigamaker_msg", value);
                this.reSize();
            },
            fontchange: function (value) {
                textbox.set({fontSize: value});
                sessionStorage.setItem("cigamaker_fontsize", value);
                this.reSize();
            },
            reSize: function () {
                var self = this;

                var heightImg = 0;
                var heightTxt = textbox.get('height');
                var heightApp = self.app_height;
                var heightRectBlack = self.height;


                rectBlack.set({height: heightTxt + 40});
                rectWhite.set({height: heightTxt + 20});


                if(rectImage !== undefined){ // значит изображение существует
                    heightImg = rectImage.get('height') * rectImage.get('scaleY'); // -1 это заляпка, чтобы убрать погрешность какую то, не ясно, кароч минус пиксеть чтобы не было отступа

                    rectBlack.set({top: 0, height: heightImg + heightTxt + 40});
                    rectWhite.set({top: heightImg + 10});
                    textbox.set({top: heightImg + 20});
                }else{
                    rectBlack.set({top: heightApp/2 - rectBlack.get('height')/2 });
                    rectWhite.set({top: heightApp/2 - rectWhite.get('height')/2 });
                    textbox.set({top: heightApp/2 - textbox.get('height')/2 });
                }



//                if( heightTxt + 40 < heightRectBlack ){ // если текст меньше высоты по умолчанию
//                    rectBlack.set({height: heightRectBlack});
//                    rectWhite.set({height: heightRectBlack - 20});
//                    var top = 0;
//                     top += heightImg ? heightImg : (heightApp/2 - textbox.get('height')/2);
//                    top += 10; // отступ
//                    top += rectWhite.get('height') / 2; // Полвина выоты белого
//                    top -= heightTxt / 2; // минус полтекста
//                    textbox.set({top: top});
//                }


//                } else if (heightTxt + 40 > self.app_height) {
//                    var fsize = textbox.get('fontSize');
//                    textbox.set({fontSize: fsize - 1});
//                }




//                $('textarea').css({'height': $('textarea')[0].scrollHeight + 2});
                canvas.renderAll();
            }
        },
        watch: {
             mess:function(newVal){
                 this.onTextExited(newVal)
             },
             fsize: function(newVal){
                 this.fontchange(newVal);
             },
             image_url: function(newVal){
                 sessionStorage.setItem("cigamaker_image", newVal);
             }
        }
    }
</script>

<style lang="scss">
    @import '../assets/styles/media';
    .fabric {
        .fabric_wrapper {
            height: calc(70vh - 62px);
            overflow-y: scroll;
            .canvas-container {
                margin: 0 auto;
            }
            @include mobile {
                color:red;
            }
        }

        .controls {
            /*height:35vh;*/
            background: #ececec;
            padding: 20px;
            position: absolute;
            bottom:0;
            margin: 0;
            width: 100%;
            box-sizing: border-box;

            textarea {
                resize: none;
                width: 100%;
                font-family: sans-serif;
            }
            input {
                margin-top: 10px;
                width: 100%;
            }
            .btn-container {
                width: 100%;
                .show_pacs {
                    ul {
                        display: flex;
                        flex-wrap: wrap;
                        list-style: none;
                        margin: 0;
                        border: 1px black solid;
                        align-items: flex-end;
                        padding: 7px;
                        li {
                            width: 25%;
                            img {
                                width: 100%;
                                display: block;
                            }
                        }
                    }
                }
                .input-container.input-group {
                    display: flex;
                }

                .btn {
                    margin-top: 10px;
                    width: 100%;
                    &.save {
                        background: $blue;
                        border:0;
                        padding: 10px;
                        color:white;
                    }
                }
            }
        }


    }
</style>