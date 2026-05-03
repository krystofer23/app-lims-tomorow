import { defineStore } from "pinia";
import { ref } from "vue";

export const usePdfViewerStore = defineStore('pdfViewerStore', () => {
    const state = ref(false)
    const url = ref(null)

    return {
        state, url
    }
})
