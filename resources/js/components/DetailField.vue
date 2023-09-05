<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <span
                v-if="field.preview"
                class="inline-block w-full"
                :class="[{ rounded: field.rounded }, field.aspect]"
                :style="{ maxWidth: `${field.maxWidth || field.detailWidth}px` }"
                v-html="field.preview"
            />
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
