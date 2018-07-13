function initialState() {
    return {
        item: {
            id: null,
            title: null,
            institution: null,
            class: null,
            category: [],
        },
        institutionsAll: [],
        classesAll: [],
        categoriesAll: [],
        
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    institutionsAll: state => state.institutionsAll,
    classesAll: state => state.classesAll,
    categoriesAll: state => state.categoriesAll,
    
}

const actions = {
    storeData({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
                if (typeof fieldValue !== 'object') {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== 'object') {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(fieldName + '[' + index + ']', fieldValue[index]);
                        }
                    }
                }
            }

            if (_.isEmpty(state.item.institution)) {
                params.set('institution_id', '')
            } else {
                params.set('institution_id', state.item.institution.id)
            }
            if (_.isEmpty(state.item.class)) {
                params.set('class_id', '')
            } else {
                params.set('class_id', state.item.class.id)
            }
            if (_.isEmpty(state.item.category)) {
                params.delete('category')
            } else {
                for (let index in state.item.category) {
                    params.set('category['+index+']', state.item.category[index].id)
                }
            }

            axios.post('/api/v1/surveys', params)
                .then(response => {
                    commit('resetState')
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message
                    let errors  = error.response.data.errors

                    dispatch(
                        'Alert/setAlert',
                        { message: message, errors: errors, color: 'danger' },
                        { root: true })

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        })
    },
    updateData({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();
            params.set('_method', 'PUT')

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
                if (typeof fieldValue !== 'object') {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== 'object') {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(fieldName + '[' + index + ']', fieldValue[index]);
                        }
                    }
                }
            }

            if (_.isEmpty(state.item.institution)) {
                params.set('institution_id', '')
            } else {
                params.set('institution_id', state.item.institution.id)
            }
            if (_.isEmpty(state.item.class)) {
                params.set('class_id', '')
            } else {
                params.set('class_id', state.item.class.id)
            }
            if (_.isEmpty(state.item.category)) {
                params.delete('category')
            } else {
                for (let index in state.item.category) {
                    params.set('category['+index+']', state.item.category[index].id)
                }
            }

            axios.post('/api/v1/surveys/' + state.item.id, params)
                .then(response => {
                    commit('setItem', response.data.data)
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message
                    let errors  = error.response.data.errors

                    dispatch(
                        'Alert/setAlert',
                        { message: message, errors: errors, color: 'danger' },
                        { root: true })

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        })
    },
    fetchData({ commit, dispatch }, id) {
        axios.get('/api/v1/surveys/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })

        dispatch('fetchInstitutionsAll')
    dispatch('fetchClassesAll')
    dispatch('fetchCategoriesAll')
    },
    fetchInstitutionsAll({ commit }) {
        axios.get('/api/v1/institutions')
            .then(response => {
                commit('setInstitutionsAll', response.data.data)
            })
    },
    fetchClassesAll({ commit }) {
        axios.get('/api/v1/classes')
            .then(response => {
                commit('setClassesAll', response.data.data)
            })
    },
    fetchCategoriesAll({ commit }) {
        axios.get('/api/v1/categories')
            .then(response => {
                commit('setCategoriesAll', response.data.data)
            })
    },
    setTitle({ commit }, value) {
        commit('setTitle', value)
    },
    setInstitution({ commit }, value) {
        commit('setInstitution', value)
    },
    setClass({ commit }, value) {
        commit('setClass', value)
    },
    setCategory({ commit }, value) {
        commit('setCategory', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item
    },
    setTitle(state, value) {
        state.item.title = value
    },
    setInstitution(state, value) {
        state.item.institution = value
    },
    setClass(state, value) {
        state.item.class = value
    },
    setCategory(state, value) {
        state.item.category = value
    },
    setInstitutionsAll(state, value) {
        state.institutionsAll = value
    },
    setClassesAll(state, value) {
        state.classesAll = value
    },
    setCategoriesAll(state, value) {
        state.categoriesAll = value
    },
    
    setLoading(state, loading) {
        state.loading = loading
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    }
}

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
