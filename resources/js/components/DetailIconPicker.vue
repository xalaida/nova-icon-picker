<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <span
                v-if="field.preview"
                class="inline-block"
                :style="{ width: `${field.detailSize}px`, height: `${field.detailSize}px` }"
                v-html="field.preview"
            />

            <span v-else>&mdash;</span>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: [
        'index',
        'resource',
        'resourceName',
        'resourceId',
        'field',
    ],
}
</script>
