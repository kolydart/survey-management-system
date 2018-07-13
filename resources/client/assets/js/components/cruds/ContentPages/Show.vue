<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Σελίδες</h1>
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
                                            <th>Τίτλος</th>
                                            <td>{{ item.title }}</td>
                                            </tr>
                                        <tr>
                                            <th>Κατηγορίες</th>
                                            <td>
                                                <span class="label label-info" v-for="category_id in item.category_id">
                                                    {{ category_id.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ετικέτες</th>
                                            <td>
                                                <span class="label label-info" v-for="tag_id in item.tag_id">
                                                    {{ tag_id.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Κείμενο</th>
                                            <td v-html="item.page_text"></td>
                                            </tr>
                                        <tr>
                                            <th>Excerpt</th>
                                            <td>{{ item.excerpt }}</td>
                                            </tr>
                                        <tr>
                                            <th>Εικόνα εμφάνισης</th>
                                            <td v-html="item.featured_image_link"></td>
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
        ...mapGetters('ContentPagesSingle', ['item'])
    },
    watch: {
        "$route.params.id": function() {
            this.resetState()
            this.fetchData(this.$route.params.id)
        }
    },
    methods: {
        ...mapActions('ContentPagesSingle', ['fetchData', 'resetState'])
    }
}
</script>


<style scoped>

</style>
