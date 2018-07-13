<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Surveys</h1>
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
                                    <label for="institution">Institution</label>
                                    <v-select
                                            name="institution"
                                            label="title"
                                            @input="updateInstitution"
                                            :value="item.institution"
                                            :options="institutionsAll"
                                            />
                                </div>
                                <div class="form-group">
                                    <label for="class">Class</label>
                                    <v-select
                                            name="class"
                                            label="title"
                                            @input="updateClass"
                                            :value="item.class"
                                            :options="classesAll"
                                            />
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <v-select
                                            name="category"
                                            label="title"
                                            @input="updateCategory"
                                            :value="item.category"
                                            :options="categoriesAll"
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
        ...mapGetters('SurveysSingle', ['item', 'loading', 'institutionsAll', 'classesAll', 'categoriesAll']),
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
        ...mapActions('SurveysSingle', ['fetchData', 'updateData', 'resetState', 'setTitle', 'setInstitution', 'setClass', 'setCategory']),
        updateTitle(e) {
            this.setTitle(e.target.value)
        },
        updateInstitution(value) {
            this.setInstitution(value)
        },
        updateClass(value) {
            this.setClass(value)
        },
        updateCategory(value) {
            this.setCategory(value)
        },
        submitForm() {
            this.updateData()
                .then(() => {
                    this.$router.push({ name: 'surveys.index' })
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
