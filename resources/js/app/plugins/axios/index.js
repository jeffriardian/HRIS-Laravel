import store from '../../states'
import axios from 'axios';

const $axios = axios.create({
    baseURL: '/api',
    headers: {
        //Authorization: localStorage.getItem('token') != 'null' ? 'Bearer ' + localStorage.getItem('token'):'',
        'Content-Type': 'application/json',
        'Accept' : 'application/json'
    }
});

//KONFIGURASINYA KITA PINDAHKAN MENGGUNAKAN INTERCEPTORS
$axios.interceptors.request.use (
    function (config) {
        const token = store.state.token
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    },
    function (error) {
        return Promise.reject (error);
    }
);

export default $axios;