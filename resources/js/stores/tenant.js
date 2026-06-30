import axios from "axios";
import { ElNotification } from "element-plus";
import { useAuthStore } from "./auth";
import router from "@/router";

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

        if (!error.response) {
            return Promise.reject(error);
        }

        const status = error.response.status;

        if ((status === 401 || status === 403) && !originalRequest._retry) {
            originalRequest._retry = true;

            try {
                const authStore = useAuthStore();

                if (!authStore.token) {
                    router.push({ name: "login" });
                }

                const { data } = await axios.post(
                    `${VITE_API_URL}/auth/refresh`,
                    {},
                    {
                        headers: {
                            Accept: "application/json",
                            Authorization: `Bearer ${localStorage.getItem("token")}`,
                        },
                    }
                );

                authStore.token = data.access_token;
                localStorage.setItem("token", data.access_token);

                originalRequest.headers.Authorization = `Bearer ${data.access_token}`;

                return tenant(originalRequest);
            } catch (e) {
                ElNotification({
                    message: "Cerrando sesión",
                    type: "error",
                });

                localStorage.removeItem("token");

                router.push({ name: "login" });

                return Promise.reject(e);
            }
        }

        return Promise.reject(error);
    }
);

export default tenant;
