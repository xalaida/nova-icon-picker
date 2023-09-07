<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div>
                <div v-if="preview" class="relative inline-block p-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 rounded-lg">
                    <span
                        class="inline-block"
                        :style="{ width: `${currentField.detailSize}px`, height: `${currentField.detailSize}px` }"
                        v-html="preview"
                    />

                    <RemoveButton
                        v-if="shouldShowResetButton"
                        type="button"
                        class="absolute z-20 top-[-10px] right-[-9px]"
                        @click="reset"
                        v-tooltip="__('Reset')"
                    />
                </div>

                <p v-if="value" class="font-semibold text-xs mt-1 mb-4">
                    {{ value }}
                </p>

                <template v-if="!currentField.readonly">
                    <div>
                        <DefaultButton
                            type="button"
                            @click="() => isSelecting = true"
                        >
                            {{ __('Select icon') }}
                        </DefaultButton>
                    </div>

                    <SelectIconModal
                        v-if="isSelecting"
                        :resource-name="resourceName"
                        :attribute="currentField.attribute"
                        :iconsets="currentField.iconsets"
                        :current-iconset="iconset"
                        :current-icon="value"
                        @select="select"
                        @close="() => isSelecting = false"
                    />
                </template>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'
import SelectIconModal from "./SelectIconModal.vue";

export default {
    components: {
        SelectIconModal
    },

    mixins: [
        DependentFormField,
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
        shouldShowResetButton() {
            return this.value
                && this.currentField.resettable
                && !this.currentlyIsReadonly
        }
    },

    methods: {
        setInitialValue() {
            this.value = this.field.value
        },

        fill(formData) {
            formData.append(this.fieldAttribute, this.value ?? '')
        },

        select(iconset, icon, contents) {
            this.iconset = iconset
            this.value = icon
            this.preview = contents
            this.isSelecting = false

            this.emitFieldValueChange(this.fieldAttribute, this.value)
        },

        reset() {
            this.value = null
            this.iconset = null
            this.preview = null
        }
    },
}
</script>
