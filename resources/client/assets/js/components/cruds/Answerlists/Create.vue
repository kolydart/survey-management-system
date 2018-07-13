<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Answerlists</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form @submit.prevent="submitForm" novalidate>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Create</h3>
                            </div>

                            <div class="box-body">
                                <back-buttton></back-buttton>
                            </div>

                            <bootstrap-alert />

                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="title"
                                            placeholder="Enter Title *"
                                            :value="item.title"
                                            @input="updateTitle"
                                            >
                                </div>
                                <div class="form-group">
                                    <label for="type">Type *</label>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="type"
                                                    :value="item.type"
                                                    :checked="item.type === 'Radio'"
                                                    @change="updateType('Radio')"
                                                    >
                                            Radio
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="type"
                                                    :value="item.type"
                                                    :checked="item.type === 'Radio + Text'"
                                                    @change="updateType('Radio + Text')"
                                                    >
                                            Radio + Text
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="type"
                                                    :value="item.type"
                                                    :checked="item.type === 'Checkbox'"
                                                    @change="updateType('Checkbox')"
                                                    >
                                            Checkbox
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="type"
                                                    :value="item.type"
                                                    :checked="item.type === 'Checkbox + Text'"
                                                    @change="updateType('Checkbox + Text')"
                                                    >
                                            Checkbox + Text
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="type"
                                                    :value="item.type"
                                                    :checked="item.type === 'Text'"
                                                    @change="updateType('Text')"
                                                    >
                                            Text
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="answers">Answers *</label>
                                    <v-select
                                            name="answers"
                                            label="title"
                                            @input="updateAnswers"
                                            :value="item.answers"
                                            :options="answersAll"
                                            multiple
                                            />
                                </div>
                            </div>

                            <div class="box-footer">
                                <vue-button-spinner
                                        class="btn btn-primary btn-sm"
                                        :isLoading="loading"
                                        :disabled="loading"
                                        >
                                    Save
                                </vue-button-spinner>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </section>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            // Code...
        }
    },
    computed: {
        ...mapGetters('AnswerlistsSingle', ['item', 'loading', 'answersAll'])
    },
    created() {
        this.fetchAnswersAll()
    },
    destroyed() {
        this.resetState()
    },
    methods: {
        ...mapActions('AnswerlistsSingle', ['storeData', 'resetState', 'setTitle', 'setType', 'setAnswers', 'fetchAnswersAll']),
        updateTitle(e) {
            this.setTitle(e.target.value)
        },
        updateType(value) {
            this.setType(value)
        },
        updateAnswers(value) {
            this.setAnswers(value)
        },
        submitForm() {
            this.storeData()
                .then(() => {
                    this.$router.push({ name: 'answerlists.index' })
                    this.$eventHub.$emit('create-success')
                })
                .catch((error) => {
                    console.error(error)
                })
        }
    }
}
</script>


<style scoped>

</style>
