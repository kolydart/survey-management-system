<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Responses</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form @submit.prevent="submitForm" novalidate>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Edit</h3>
                            </div>

                            <div class="box-body">
                                <back-buttton></back-buttton>
                            </div>

                            <bootstrap-alert />

                            <div class="box-body">
                                <div class="form-group">
                                    <label for="question">Question *</label>
                                    <v-select
                                            name="question"
                                            label="title"
                                            @input="updateQuestion"
                                            :value="item.question"
                                            :options="questionsAll"
                                            />
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea
                                            rows="3"
                                            class="form-control"
                                            name="content"
                                            placeholder="Enter Content"
                                            :value="item.content"
                                            @input="updateContent"
                                            >
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="answer">Answer *</label>
                                    <v-select
                                            name="answer"
                                            label="title"
                                            @input="updateAnswer"
                                            :value="item.answer"
                                            :options="answersAll"
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
        ...mapGetters('ResponsesSingle', ['item', 'loading', 'questionsAll', 'answersAll']),
    },
    created() {
        this.fetchData(this.$route.params.id)
    },
    destroyed() {
        this.resetState()
    },
    watch: {
        "$route.params.id": function() {
            this.resetState()
            this.fetchData(this.$route.params.id)
        }
    },
    methods: {
        ...mapActions('ResponsesSingle', ['fetchData', 'updateData', 'resetState', 'setQuestion', 'setContent', 'setAnswer']),
        updateQuestion(value) {
            this.setQuestion(value)
        },
        updateContent(e) {
            this.setContent(e.target.value)
        },
        updateAnswer(value) {
            this.setAnswer(value)
        },
        submitForm() {
            this.updateData()
                .then(() => {
                    this.$router.push({ name: 'responses.index' })
                    this.$eventHub.$emit('update-success')
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
