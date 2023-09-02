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
                    v-if="value"
                    style="width: 150px; height: 150px"
                    class="mb-4 bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-2 border-gray-200 dark:border-gray-700 overflow-hidden rounded-lg"
                    v-html="value.icon"
                />

                <!-- @todo add custom details -->
                <!-- <p class="font-semibold text-xs mt-1">Icon details here...</p>-->

                <!-- <input-->
                <!--     :id="field.attribute"-->
                <!--     type="text"-->
                <!--     class="w-full form-control form-input form-input-bordered"-->
                <!--     :class="errorClasses"-->
                <!--     :placeholder="field.name"-->
                <!--     v-model="value"-->
                <!-- />-->

                <DefaultButton
                    @click="() => isSelecting = true"
                >
                    Select icon
                </DefaultButton>

                <SelectIconModal
                    v-if="isSelecting"
                    :sets="field.sets"
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

    data: () => ({
        isSelecting: true
    }),

    methods: {
        setInitialValue() {
            this.value = this.field.value
        },

        fill(formData) {
            // if (this.value) {
            //     formData.append(this.fieldAttribute, JSON.stringify(this.value))
            // } else {
            //     formData.append(this.fieldAttribute, '')
            // }
        },

        setIcon(icon) {
            this.value = icon

            this.emitFieldValueChange(this.fieldAttribute, this.value)
        },

        onSelect(icon) {
            this.setIcon(icon)

            this.isSelecting = false
        },
    },
}
</script>
