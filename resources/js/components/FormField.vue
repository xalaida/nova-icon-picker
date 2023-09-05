<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div>
                <div
                    v-if="preview"
                    style="width: 150px; height: 150px"
                    class="mb-4 bg-gray-50 dark:bg-gray-700 relative aspect-square p-4 flex items-center justify-center border-2 border-gray-200 dark:border-gray-700 overflow-hidden rounded-lg"
                    v-html="preview"
                />

                <!-- @todo add custom details -->
                <!-- <p class="font-semibold text-xs mt-1">Icon details here...</p>-->

                <DefaultButton
                    type="button"
                    @click="() => isSelecting = true"
                >
                    Select icon
                </DefaultButton>

                <SelectIconModal
                    v-if="isSelecting"
                    :resource-name="resourceName"
                    :attribute="field.attribute"
                    :iconsets="field.iconsets"
                    :current-iconset="iconset"
                    :current-icon="value"
                    @close="isSelecting = false"
                    @select="onSelect"
                />
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import SelectIconModal from "./SelectIconModal.vue";

export default {
    components: {
        SelectIconModal
    },

    mixins: [
        FormField,
        HandlesValidationErrors
    ],

    props: [
        'resourceName',
        'resourceId',
        'field',
    ],

    data() {
        return {
            preview: this.field.preview,
            iconset: this.field.iconset,
            isSelecting: false
        }
    },

    methods: {
        setInitialValue() {
            this.value = this.field.value
        },

        fill(formData) {
            formData.append(this.fieldAttribute, this.value)
        },

        setIcon(name, contents) {
            this.value = name
            this.preview = contents

            this.emitFieldValueChange(this.fieldAttribute, this.value)
        },

        onSelect(iconset, icon, contents) {
            this.setIcon(icon, contents)
            this.iconset = iconset

            this.isSelecting = false
        }
    },
}
</script>
