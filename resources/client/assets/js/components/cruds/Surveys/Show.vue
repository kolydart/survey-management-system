<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Surveys</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">View</h3>
                        </div>

                        <div class="box-body">
                            <back-buttton></back-buttton>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <th>#</th>
                                            <td>{{ item.id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td>{{ item.title }}</td>
                                            </tr>
                                        <tr>
                                            <th>Institution</th>
                                            <td>
                                                <span class="label label-info" v-if="item.institution !== null">
                                                    {{ item.institution.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>
                                                <span class="label label-info" v-for="category in item.category">
                                                    {{ category.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Class</th>
                                            <td>
                                                <span class="label label-info" v-if="item.group !== null">
                                                    {{ item.group.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Introduction</th>
                                            <td>{{ item.introduction }}</td>
                                            </tr>
                                        <tr>
                                            <th>Notes</th>
                                            <td>{{ item.notes }}</td>
                                            </tr>
                                        <tr>
                                            <th>Completed</th>
                                            <td>{{ item.completed }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
    created() {
        this.fetchData(this.$route.params.id)
    },
    destroyed() {
        this.resetState()
    },
    computed: {
        ...mapGetters('SurveysSingle', ['item'])
    },
    watch: {
        "$route.params.id": function() {
            this.resetState()
            this.fetchData(this.$route.params.id)
        }
    },
    methods: {
        ...mapActions('SurveysSingle', ['fetchData', 'resetState'])
    }
}
</script>


<style scoped>

</style>
