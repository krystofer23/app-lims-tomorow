import { defineStore } from "pinia";
import { ref, watch } from "vue";
import { handleErrorsExeption } from "./handleErrorsExeption";
import tenant from "./tenant";

export const useOsViewModalStore = defineStore('osViewModalStore', () => {

    const state = ref(false)
    const order = ref(null)
    const orderId = ref(null)
    const loading = ref(false)

    const getOrder = async () => {
        loading.value = true

        try {
            const { data } = await tenant.get(`order-service/${orderId.value}`)

            if (data.data) {
                order.value = data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            loading.value = false
        }
    }

    watch(() => orderId.value, (newVal) => {
        if (newVal) {
            getOrder()
        }
    })

    return {
        state, order, orderId, loading
    }
})