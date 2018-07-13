function initialState() {
    return {
        item: {
            id: null,
            title: null,
            category_id: [],
            tag_id: [],
            page_text: null,
            excerpt: null,
            featured_image: null,
        },
        contentcategoriesAll: [],
        contenttagsAll: [],
        
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    contentcategoriesAll: state => state.contentcategoriesAll,
    contenttagsAll: state => state.contenttagsAll,
    
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

            if (_.isEmpty(state.item.category_id)) {
                params.delete('category_id')
            } else {
                for (let index in state.item.category_id) {
                    params.set('category_id['+index+']', state.item.category_id[index].id)
                }
            }
            if (_.isEmpty(state.item.tag_id)) {
                params.delete('tag_id')
            } else {
                for (let index in state.item.tag_id) {
                    params.set('tag_id['+index+']', state.item.tag_id[index].id)
                }
            }
            if (state.item.featured_image === null) {
                params.delete('featured_image');
            }

            axios.post('/api/v1/content-pages', params)
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

            if (_.isEmpty(state.item.category_id)) {
                params.delete('category_id')
            } else {
                for (let index in state.item.category_id) {
                    params.set('category_id['+index+']', state.item.category_id[index].id)
                }
            }
            if (_.isEmpty(state.item.tag_id)) {
                params.delete('tag_id')
            } else {
                for (let index in state.item.tag_id) {
                    params.set('tag_id['+index+']', state.item.tag_id[index].id)
                }
            }
            if (state.item.featured_image === null) {
                params.delete('featured_image');
            }

            axios.post('/api/v1/content-pages/' + state.item.id, params)
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
        axios.get('/api/v1/content-pages/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })

        dispatch('fetchContentcategoriesAll')
    dispatch('fetchContenttagsAll')
    },
    fetchContentcategoriesAll({ commit }) {
        axios.get('/api/v1/content-categories')
            .then(response => {
                commit('setContentcategoriesAll', response.data.data)
            })
    },
    fetchContenttagsAll({ commit }) {
        axios.get('/api/v1/content-tags')
            .then(response => {
                commit('setContenttagsAll', response.data.data)
            })
    },
    setTitle({ commit }, value) {
        commit('setTitle', value)
    },
    setCategory_id({ commit }, value) {
        commit('setCategory_id', value)
    },
    setTag_id({ commit }, value) {
        commit('setTag_id', value)
    },
    setPage_text({ commit }, value) {
        commit('setPage_text', value)
    },
    setExcerpt({ commit }, value) {
        commit('setExcerpt', value)
    },
    setFeatured_image({ commit }, value) {
        commit('setFeatured_image', value)
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
    setCategory_id(state, value) {
        state.item.category_id = value
    },
    setTag_id(state, value) {
        state.item.tag_id = value
    },
    setPage_text(state, value) {
        state.item.page_text = value
    },
    setExcerpt(state, value) {
        state.item.excerpt = value
    },
    setFeatured_image(state, value) {
        state.item.featured_image = value
    },
    setContentcategoriesAll(state, value) {
        state.contentcategoriesAll = value
    },
    setContenttagsAll(state, value) {
        state.contenttagsAll = value
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
