<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Items</h1>
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
                                    <label for="survey">Survey *</label>
                                    <v-select
                                            name="survey"
                                            label="title"
                                            @input="updateSurvey"
                                            :value="item.survey"
                                            :options="surveysAll"
                                            />
                                </div>
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
                                    <label for="order">Order</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="order"
                                            placeholder="Enter Order"
                                            :value="item.order"
                                            @input="updateOrder"
                                            >
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
        ...mapGetters('ItemsSingle', ['item', 'loading', 'surveysAll', 'questionsAll']),
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
        ...mapActions('ItemsSingle', ['fetchData', 'updateData', 'resetState', 'setSurvey', 'setQuestion', 'setOrder']),
        updateSurvey(value) {
            this.setSurvey(value)
        },
        updateQuestion(value) {
            this.setQuestion(value)
        },
        updateOrder(e) {
            this.setOrder(e.target.value)
        },
        submitForm() {
            this.updateData()
                .then(() => {
                    this.$router.push({ name: 'items.index' })
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
