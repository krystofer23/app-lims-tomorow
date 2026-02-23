import axios from "axios";

const api = axios.create({
    baseURL: `${import.meta.env.VITE_API_URL}/`,
    headers: {
        Accept: 'application/json',
    },
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
})

api.interceptors.response.use(
    async error => {
        const originalRequest = error.config;

        if (error.response && error.response.status === 403) {
            ElNotification({
                message: `
                        Cerrando sesión
                    `,
                type: 'error',
                dangerouslyUseHTMLString: true
            });
        }
        if (error.response && error.response.status === 401 && !originalRequest._retry) {
            // originalRequest._retry = true;

            // try {
            //     const authStore = useAuthStore();

            //     const { data } = await axios.post(
            //         `${import.meta.env.VITE_API_URL}/auth/refresh`, {}, authStore.headers());

            //     authStore.token = data.access_token;
            //     localStorage.setItem('token', data.access_token);

            //     originalRequest.headers.Authorization = `Bearer ${data.access_token}`;
            //     return api(originalRequest);
            // }
            // catch (e) {
            //     ElNotification({
            //         message: `
            //             Cerrando sesión
            //         `,
            //         type: 'error',
            //         dangerouslyUseHTMLString: true
            //     });

            //     localStorage.removeItem('token');
            //     localStorage.removeItem('user');

            //     router.push({ name: 'home' });
            // }
        }

        return Promise.reject(error);
    }
);

export default api;