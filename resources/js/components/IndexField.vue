<template>
    <span
        v-if="field.preview"
        class="inline-block w-full"
        :class="[{ rounded: field.rounded }, field.aspect]"
        :style="{ maxWidth: `${field.maxWidth || field.indexWidth}px` }"
        v-html="field.preview"
    />
</template>

<script>
export default {
    props: [
        'resourceName',
        'field',
    ]
}
</script>
