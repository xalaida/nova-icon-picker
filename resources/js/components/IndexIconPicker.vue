<template>
    <span>
        <span
            v-if="field.contents"
            class="inline-block"
            :style="{ width: `${field.indexSize}px`, height: `${field.indexSize}px` }"
            v-html="field.contents"
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
