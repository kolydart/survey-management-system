<template>
    <div v-html="row.featured_image_link"></div>
</template>


<script>
    export default {
        props: ['row'],
    }
</script>
