import axios from 'axios';
import { useToast } from 'vue-toastification';

const api = {};
let instance = null;

api.install = function (app, options) {
    const toast = useToast();

    const createInstance = (apiKey) => {
        const axiosInstance = axios.create({
            baseURL: options.baseURL,
            headers: {
                'Content-type': 'application/json',
            }
        });

        return axiosInstance;
    };

    instance = createInstance(options.apiKey);

    app.config.globalProperties.$api = instance;
    app.provide('api', instance);
}

export default api;