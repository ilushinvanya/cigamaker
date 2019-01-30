import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        authUser: false,
        gifs: ['http://media1.giphy.com/media/QHF8JKq17qATu/200.gif',
            'https://i.pinimg.com/originals/8f/e0/db/8fe0dbf9ccd6752f37720d796524c854.jpg',
            'https://media.giphy.com/media/GNDipZXG4qJsk/giphy.gif'
            // '@/assets/gif/01.gif',
            // 'img/gif/02.gif'
        ]
    },
    getters: {
        auth_url(){
            var url = '';
            if(process.env.NODE_ENV === 'production'){
                url = "http://cigamaker.ru/register";
            }else{
                url = "http://localhost/register"
            }
            var string = `https://oauth.vk.com/authorize?client_id=4239560&display=popup&redirect_uri=${url}&scope=&response_type=token&v=5.74&state=`;
            return string;
        },
        randGifs(state){
            function compareRandom(a, b) {
                return Math.random() - 0.5;
            }
            return state.gifs.sort(compareRandom);
        },
        mobile(){
            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            return isMobile;
        }
    },
    mutations: {
        setUser (state, data_from_vk){
            data_from_vk["uid"] = data_from_vk["id"];
            delete data_from_vk["id"];

            var temp_user_obj = data_from_vk;

            var postec = new FormData();
            postec.append('method', 'user_auth');
            postec.append('data', JSON.stringify(data_from_vk));

            Vue.http.post("php/index.php", postec).then(response => {
                if (response.body.success) {
                    temp_user_obj.id = +response.body.data[0].id;
                    state.authUser = temp_user_obj;
                }
            }, response => {
                // error callback
            });

        }
    },
    actions:{
        get_data_from_vk({commit}){
            var access_token = localStorage.getItem('cigamaker_token');
            var user_id = localStorage.getItem('cigamaker_user');

            Vue.http.jsonp("https://api.vk.com/method/users.get?user_ids=" + user_id + "&v=5.74&access_token=" + access_token).then(response => {
                if(response.body.error){
                    // Если приходит плохой ответ, то ни коммита, и локал сторадж удаляем
                    localStorage.removeItem('cigamaker_token');
                    localStorage.removeItem('cigamaker_user');
                }else if(response.body.response){
                    commit('setUser', response.body.response[0]);
                }else{

                }


            }, response => {
                // error callback
            });
        }
    }
});
