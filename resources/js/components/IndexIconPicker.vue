<template>
    <span>
        <span
            v-if="field.preview"
            class="inline-block"
            :style="{ width: `${field.indexSize}px`, height: `${field.indexSize}px` }"
            v-html="field.preview"
        />

        <span v-else>&mdash;</span>
    </span>
</template>

<script>
export default {
    props: [
        'resourceName',
        'field',
    ]
}
</script>
