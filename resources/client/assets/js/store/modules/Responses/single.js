function initialState() {
    return {
        item: {
            id: null,
            question: null,
            content: null,
            answer: null,
        },
        questionsAll: [],
        answersAll: [],
        
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    questionsAll: state => state.questionsAll,
    answersAll: state => state.answersAll,
    
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

            if (_.isEmpty(state.item.question)) {
                params.set('question_id', '')
            } else {
                params.set('question_id', state.item.question.id)
            }
            if (_.isEmpty(state.item.answer)) {
                params.set('answer_id', '')
            } else {
                params.set('answer_id', state.item.answer.id)
            }

            axios.post('/api/v1/responses', params)
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

            if (_.isEmpty(state.item.question)) {
                params.set('question_id', '')
            } else {
                params.set('question_id', state.item.question.id)
            }
            if (_.isEmpty(state.item.answer)) {
                params.set('answer_id', '')
            } else {
                params.set('answer_id', state.item.answer.id)
            }

            axios.post('/api/v1/responses/' + state.item.id, params)
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
        axios.get('/api/v1/responses/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })

        dispatch('fetchQuestionsAll')
    dispatch('fetchAnswersAll')
    },
    fetchQuestionsAll({ commit }) {
        axios.get('/api/v1/questions')
            .then(response => {
                commit('setQuestionsAll', response.data.data)
            })
    },
    fetchAnswersAll({ commit }) {
        axios.get('/api/v1/answers')
            .then(response => {
                commit('setAnswersAll', response.data.data)
            })
    },
    setQuestion({ commit }, value) {
        commit('setQuestion', value)
    },
    setContent({ commit }, value) {
        commit('setContent', value)
    },
    setAnswer({ commit }, value) {
        commit('setAnswer', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item
    },
    setQuestion(state, value) {
        state.item.question = value
    },
    setContent(state, value) {
        state.item.content = value
    },
    setAnswer(state, value) {
        state.item.answer = value
    },
    setQuestionsAll(state, value) {
        state.questionsAll = value
    },
    setAnswersAll(state, value) {
        state.answersAll = value
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