<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Σελίδες</h1>
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
                                    <label for="title">Τίτλος *</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="title"
                                            placeholder="Enter Τίτλος *"
                                            :value="item.title"
                                            @input="updateTitle"
                                            >
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Κατηγορίες</label>
                                    <v-select
                                            name="category_id"
                                            label="title"
                                            @input="updateCategory_id"
                                            :value="item.category_id"
                                            :options="contentcategoriesAll"
                                            multiple
                                            />
                                </div>
                                <div class="form-group">
                                    <label for="tag_id">Ετικέτες</label>
                                    <v-select
                                            name="tag_id"
                                            label="title"
                                            @input="updateTag_id"
                                            :value="item.tag_id"
                                            :options="contenttagsAll"
                                            multiple
                                            />
                                </div>
                                <div class="form-group">
                                    <label for="page_text">Κείμενο</label>
                                    <vue-ckeditor
                                            name="page_text"
                                            :value="item.page_text"
                                            @input="updatePage_text"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea
                                            rows="3"
                                            class="form-control"
                                            name="excerpt"
                                            placeholder="Enter Excerpt"
                                            :value="item.excerpt"
                                            @input="updateExcerpt"
                                            >
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="featured_image">Εικόνα εμφάνισης</label>
                                    <input
                                            type="file"
                                            class="form-control"
                                            @change="updateFeatured_image"
                                    >
                                    <ul v-if="item.featured_image" class="list-unstyled">
                                        <li>
                                            {{ item.featured_image.name || item.featured_image.file_name }}
                                            <button class="btn btn-xs btn-danger"
                                                    type="button"
                                                    @click="removeFeatured_image"
                                            >
                                                Remove file
                                            </button>
                                        </li>
                                    </ul>
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
        ...mapGetters('ContentPagesSingle', ['item', 'loading', 'contentcategoriesAll', 'contenttagsAll'])
    },
    created() {
        this.fetchContentcategoriesAll(),
        this.fetchContenttagsAll()
    },
    destroyed() {
        this.resetState()
    },
    methods: {
        ...mapActions('ContentPagesSingle', ['storeData', 'resetState', 'setTitle', 'setCategory_id', 'setTag_id', 'setPage_text', 'setExcerpt', 'setFeatured_image', 'fetchContentcategoriesAll', 'fetchContenttagsAll']),
        updateTitle(e) {
            this.setTitle(e.target.value)
        },
        updateCategory_id(value) {
            this.setCategory_id(value)
        },
        updateTag_id(value) {
            this.setTag_id(value)
        },
        updatePage_text(value) {
            this.setPage_text(value)
        },
        updateExcerpt(e) {
            this.setExcerpt(e.target.value)
        },
        removeFeatured_image(e, id) {
            this.$swal({
                title: 'Are you sure?',
                text: "To fully delete the file submit the form.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#dd4b39',
                focusCancel: true,
                reverseButtons: true
            }).then(result => {
                if (typeof result.dismiss === "undefined") {
                    this.setFeatured_image('');
                }
            })
        },
        updateFeatured_image(e) {
            this.setFeatured_image(e.target.files[0]);
            this.$forceUpdate();
        },
        submitForm() {
            this.storeData()
                .then(() => {
                    this.$router.push({ name: 'content_pages.index' })
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
