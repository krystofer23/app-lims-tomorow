import { useRouter } from "vue-router";
import { handleErrorsExeption } from "./handleErrorsExeption";
import tenant from "./tenant";
import { defineStore } from "pinia";
import { ref } from "vue";
import { ElNotification } from "element-plus";

export const useAuthStore = defineStore("authStore", () => {

    const token = ref(localStorage.getItem("token") ?? null)
    const user = ref(JSON.parse(localStorage.getItem("user") ?? "null"))
    const router = useRouter()

    const headers = () => {
        return {
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token.value,
            },
        };
    }

    const headersMultipart = () => {
        return {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: "Bearer " + token.value,
            },
        };
    };

    const login = async (email, password, roleParam) => {
        try {
            const { data } = await tenant.post(`auth`, {
                email: email,
                password: password,
                role: roleParam
            });

            if (data.data) {
                user.value = data.data.user
                token.value = data.data.access_token

                localStorage.setItem("token", token.value)
                localStorage.setItem("user", JSON.stringify(user.value))

                ElNotification.success(data.message)

                router.push({ name: 'home' })
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const me = async () => {
        try {
            const { data } = await api.get(`auth/me`);

            if (data.data) {
                user.value = data.data.user;
                localStorage.setItem("user", JSON.stringify(user.value));
            }
        }
        catch (e) {
            handleErrors(e);
        }
    }

    const logout = async () => {
        try {
            const { data } = await api.post(`auth/logout`);
            ElNotification.success(data.message);

            localStorage.removeItem('token')
            localStorage.removeItem('user')

            token.value = null
            user.value = null

            router.push({ name: 'login' })
        }
        catch (e) {
            localStorage.removeItem('token')
            localStorage.removeItem('user')

            token.value = null
            user.value = null

            router.push({ name: 'login' })
        }
    }

    return {
        token, user, headers, headersMultipart, login, logout, me
    }
})