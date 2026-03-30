import axios from "axios";
import { ElNotification } from "element-plus";
import { useAuthStore } from "./auth";
import { useRouter } from "vue-router";

const domain = window.location.hostname;
const VITE_API_URL = `http://${domain}/tenant`;

const tenant = axios.create({
    baseURL: `${VITE_API_URL}/`,
    headers: {
        Accept: "application/json",
    },
});

tenant.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

tenant.interceptors.response.use(
    (response) => response,

    async (error) => {
        const originalRequest = error.config;
        const router = useRouter()

        if (error.response && error.response.status === 403) {
            try {
                const authStore = useAuthStore();

                const { data } = await tenant.post(`auth/refresh`, {}, authStore.headers());

                authStore.token = data.access_token;
                localStorage.setItem('token', data.access_token);

                originalRequest.headers.Authorization = `Bearer ${data.access_token}`;
                return tenant(originalRequest);
            }
            catch (e) {
                // ElNotification({
                //     message: `
                //         Cerrando sesión
                //     `,
                //     type: 'error',
                //     dangerouslyUseHTMLString: true
                // });

                // localStorage.removeItem('token');
                // localStorage.removeItem('user');

                // router.push({ name: 'login' });
            }
        }

        if (error.response && error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;

            try {
                const authStore = useAuthStore();

                const { data } = await tenant.post(`auth/refresh`, {}, authStore.headers());

                authStore.token = data.access_token;
                localStorage.setItem('token', data.access_token);

                originalRequest.headers.Authorization = `Bearer ${data.access_token}`;
                return tenant(originalRequest);
            }
            catch (e) {
                // ElNotification({
                //     message: `
                //         Cerrando sesión
                //     `,
                //     type: 'error',
                //     dangerouslyUseHTMLString: true
                // });

                // localStorage.removeItem('token');
                // localStorage.removeItem('user');

                // router.push({ name: 'login' });
            }
        }

        return Promise.reject(error);
    }
);

export default tenant;