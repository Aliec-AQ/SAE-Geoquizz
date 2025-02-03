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

        axiosInstance.interceptors.response.use(
            response => response,
            error => {
                toast.error(`API Error: ${error.message}`);
                return Promise.reject(error);
            }
        );

        return axiosInstance;
    };

    instance = createInstance(options.apiKey);

    app.config.globalProperties.$api = instance;
}

export default api;