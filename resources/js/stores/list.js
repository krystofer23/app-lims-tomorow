import { defineStore } from "pinia";
import { ref } from "vue";
import tenant from "./tenant";
import { handleErrorsExeption } from "./handleErrorsExeption";

export const useListStore = defineStore("listStore", () => {
    const conditions = ref([])
    const methodologies = ref([])
    const unitsMeasurement = ref([])
    const companies = ref([])
    const matrizDescription = ref([])
    const services = ref([])
    const comerciales = ref([])

    const contacts = ref([])
    const loadingContacts = ref(false)

    const essays = ref([])
    const paginationEssays = ref({
        last_page: 0,
        current_page: 0,
        total: 0,
        per_page: 0,
    })

    const loadingService = ref(false)
    const paginationService = ref({
        last_page: 0,
        current_page: 0,
        total: 0,
        per_page: 0,
    })

    const getMatrizDescription = async () => {
        try {
            const { data } = await tenant.get(`list/matriz-description`)

            if (data.data) {
                matrizDescription.value = data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getConditions = async (q = null, page = 1) => {
        try {
            const { data } = await tenant.get(`list/conditions?page=${page}`, {
                params: {
                    query: q
                }
            })

            if (data.data) {
                conditions.value = data.data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getUnitsMeasurement = async (q = null, page = 1) => {
        try {
            const { data } = await tenant.get(`list/units-measurement?page=${page}`, {
                params: {
                    query: q
                }
            })

            if (data.data) {
                unitsMeasurement.value = data.data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getMethodologies = async (q = null, page = 1) => {
        try {
            const { data } = await tenant.get(`list/methodologies?page=${page}`, {
                params: {
                    query: q
                }
            })

            if (data.data) {
                methodologies.value = data.data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getEssays = async (q = null, page = 1) => {
        try {
            const { data } = await tenant.get(`list/essays?page=${page}`, {
                params: {
                    query: q
                }
            })

            if (data.data) {
                essays.value = data.data.data
                paginationEssays.value = {
                    last_page: data.data.last_page,
                    current_page: data.data.current_page,
                    total: data.data.total,
                    per_page: data.data.per_page,
                }
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getCompanies = async (q = null, page = 1) => {
        try {
            const { data } = await tenant.get(`list/companies?page=${page}`, {
                params: {
                    query: q
                }
            })

            if (data.data) {
                companies.value = data.data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }

    const getServices = async (q = null, page = 1) => {
        loadingService.value = true

        try {
            const { data } = await tenant.get(`list/services?page=${page}`, {
                params: {
                    params: {
                        query: q
                    }
                }
            })

            if (data.data) {
                services.value = data.data.data
                paginationService.value = {
                    last_page: data.data.last_page,
                    current_page: data.data.current_page,
                    total: data.data.total,
                    per_page: data.data.per_page,
                }
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            loadingService.value = false
        }
    }

    const getContacts = async (q = null, company_id = null, page = 1) => {
        if (!company_id) {
            return
        }

        loadingContacts.value = true

        try {
            const { data } = await tenant.get(`list/contacts?page=${page}`, {
                params: {
                    query: q,
                    company_id: company_id
                }
            })

            if (data.data) {
                contacts.value = data.data.data
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            loadingContacts.value = false
        }
    }

    const loadingTeam = ref(false)
    const teams = ref([])
    const paginationTeam = ref({
        last_page: 0,
        current_page: 0,
        total: 0,
        per_page: 0,
    })

    const getTeams = async (page = 1, params = {}) => {
        loadingTeam.value = true

        try {
            const { data } = await tenant.get(`list/teams?page=${page}`, {
                params: params
            })

            if (data.data) {
                teams.value = data.data.data

                paginationTeam.value = {
                    last_page: data.data.last_page,
                    current_page: data.data.current_page,
                    total: data.data.total,
                    per_page: data.data.per_page,
                }
            }
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            loadingTeam.value = false
        }
    }

    return {
        conditions, unitsMeasurement, methodologies, essays, paginationEssays, companies, getMatrizDescription, services, loadingService, paginationService, comerciales,
        getConditions, getUnitsMeasurement, getMethodologies, getEssays, getCompanies, matrizDescription, getServices, contacts, loadingContacts, getContacts, loadingTeam, 
        teams, paginationTeam, getTeams
    }
})