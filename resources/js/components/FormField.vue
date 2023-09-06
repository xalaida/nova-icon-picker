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
                    class="relative inline-block p-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 rounded-lg"
                >
                    <div
                        :class="[{ rounded: field.rounded }, field.aspect]"
                        :style="{ width: `${field.detailWidth}px` }"
                        v-html="preview"
                    />

                    <RemoveButton
                        v-if="shouldShowRemoveButton"
                        type="button"
                        class="absolute z-20 top-[-10px] right-[-9px]"
                        @click="onRemove"
                        v-tooltip="__('Remove')"
                    />
                </div>

                <p v-if="value" class="font-semibold text-xs mt-1 mb-4">
                    {{ value }}
                </p>

                <div>
                    <DefaultButton
                        type="button"
                        @click="() => isSelecting = true"
                    >
                        Select icon
                    </DefaultButton>
                </div>

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
import {FormField, HandlesValidationErrors} from 'laravel-nova'
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

    computed: {
        shouldShowRemoveButton() {
            return this.value && this.currentField.nullable
                // && !this.currentlyIsReadonly
        }
    },

    methods: {
        setInitialValue() {
            this.value = this.field.value
        },

        fill(formData) {
            formData.append(this.fieldAttribute, this.value ?? '')
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
        },

        onRemove() {
            this.value = null
            this.iconset = null
            this.preview = null
        }
    },
}
</script>
