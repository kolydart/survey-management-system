function initialState() {
    return {
        item: {
            id: null,
            title: null,
            institution: null,
            category: [],
            group: null,
            introduction: null,
            notes: null,
            access: null,
            completed: false,
        },
        institutionsAll: [],
        categoriesAll: [],
        groupsAll: [],
        
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    institutionsAll: state => state.institutionsAll,
    categoriesAll: state => state.categoriesAll,
    groupsAll: state => state.groupsAll,
    
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
            if (_.isEmpty(state.item.category)) {
                params.delete('category')
            } else {
                for (let index in state.item.category) {
                    params.set('category['+index+']', state.item.category[index].id)
                }
            }
            if (_.isEmpty(state.item.group)) {
                params.set('group_id', '')
            } else {
                params.set('group_id', state.item.group.id)
            }
            params.set('completed', state.item.completed ? 1 : 0)

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
            if (_.isEmpty(state.item.category)) {
                params.delete('category')
            } else {
                for (let index in state.item.category) {
                    params.set('category['+index+']', state.item.category[index].id)
                }
            }
            if (_.isEmpty(state.item.group)) {
                params.set('group_id', '')
            } else {
                params.set('group_id', state.item.group.id)
            }
            params.set('completed', state.item.completed ? 1 : 0)

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
    dispatch('fetchCategoriesAll')
    dispatch('fetchGroupsAll')
    },
    fetchInstitutionsAll({ commit }) {
        axios.get('/api/v1/institutions')
            .then(response => {
                commit('setInstitutionsAll', response.data.data)
            })
    },
    fetchCategoriesAll({ commit }) {
        axios.get('/api/v1/categories')
            .then(response => {
                commit('setCategoriesAll', response.data.data)
            })
    },
    fetchGroupsAll({ commit }) {
        axios.get('/api/v1/groups')
            .then(response => {
                commit('setGroupsAll', response.data.data)
            })
    },
    setTitle({ commit }, value) {
        commit('setTitle', value)
    },
    setInstitution({ commit }, value) {
        commit('setInstitution', value)
    },
    setCategory({ commit }, value) {
        commit('setCategory', value)
    },
    setGroup({ commit }, value) {
        commit('setGroup', value)
    },
    setIntroduction({ commit }, value) {
        commit('setIntroduction', value)
    },
    setNotes({ commit }, value) {
        commit('setNotes', value)
    },
    setAccess({ commit }, value) {
        commit('setAccess', value)
    },
    setCompleted({ commit }, value) {
        commit('setCompleted', value)
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
    setCategory(state, value) {
        state.item.category = value
    },
    setGroup(state, value) {
        state.item.group = value
    },
    setIntroduction(state, value) {
        state.item.introduction = value
    },
    setNotes(state, value) {
        state.item.notes = value
    },
    setAccess(state, value) {
        state.item.access = value
    },
    setCompleted(state, value) {
        state.item.completed = value
    },
    setInstitutionsAll(state, value) {
        state.institutionsAll = value
    },
    setCategoriesAll(state, value) {
        state.categoriesAll = value
    },
    setGroupsAll(state, value) {
        state.groupsAll = value
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
