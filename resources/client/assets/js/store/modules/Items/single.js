function initialState() {
    return {
        item: {
            id: null,
            survey: null,
            question: null,
            order: null,
        },
        surveysAll: [],
        questionsAll: [],
        
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    surveysAll: state => state.surveysAll,
    questionsAll: state => state.questionsAll,
    
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

            if (_.isEmpty(state.item.survey)) {
                params.set('survey_id', '')
            } else {
                params.set('survey_id', state.item.survey.id)
            }
            if (_.isEmpty(state.item.question)) {
                params.set('question_id', '')
            } else {
                params.set('question_id', state.item.question.id)
            }

            axios.post('/api/v1/items', params)
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

            if (_.isEmpty(state.item.survey)) {
                params.set('survey_id', '')
            } else {
                params.set('survey_id', state.item.survey.id)
            }
            if (_.isEmpty(state.item.question)) {
                params.set('question_id', '')
            } else {
                params.set('question_id', state.item.question.id)
            }

            axios.post('/api/v1/items/' + state.item.id, params)
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
        axios.get('/api/v1/items/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })

        dispatch('fetchSurveysAll')
    dispatch('fetchQuestionsAll')
    },
    fetchSurveysAll({ commit }) {
        axios.get('/api/v1/surveys')
            .then(response => {
                commit('setSurveysAll', response.data.data)
            })
    },
    fetchQuestionsAll({ commit }) {
        axios.get('/api/v1/questions')
            .then(response => {
                commit('setQuestionsAll', response.data.data)
            })
    },
    setSurvey({ commit }, value) {
        commit('setSurvey', value)
    },
    setQuestion({ commit }, value) {
        commit('setQuestion', value)
    },
    setOrder({ commit }, value) {
        commit('setOrder', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item
    },
    setSurvey(state, value) {
        state.item.survey = value
    },
    setQuestion(state, value) {
        state.item.question = value
    },
    setOrder(state, value) {
        state.item.order = value
    },
    setSurveysAll(state, value) {
        state.surveysAll = value
    },
    setQuestionsAll(state, value) {
        state.questionsAll = value
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
