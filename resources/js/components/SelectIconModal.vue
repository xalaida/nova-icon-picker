<template>
    <Modal
        show
        size="2xl"
        role="alertdialog"
        @close-via-escape="$emit('close')"
    >
        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800">
            <ModalHeader class="flex items-center">
                Select icon
            </ModalHeader>

            <ModalContent class="px-8 divide-y divide-gray-100 dark:divide-gray-800 -mx-3">
                <!-- @todo add button to select icon from local computer -->
                <textarea
                    type="text"
                    class="block w-full form-control form-input form-input-bordered py-3 h-auto"
                    rows="5"
                    :placeholder="__('Paste SVG...')"
                    v-model="rawSvgIcon"
                />
            </ModalContent>

            <ModalFooter>
                <div class="ml-auto space-x-3">
                    <BasicButton
                        type="button"
                        @click="$emit('close')"
                    >
                        {{ __('Cancel') }}
                    </BasicButton>

                    <DefaultButton
                        type="submit"
                    >
                        {{ __('Confirm') }}
                    </DefaultButton>
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'

const emits = defineEmits(['close', 'select'])

const rawSvgIcon = ref()

const submit = () => {
    console.log('submitting...')
    emits('select', rawSvgIcon)
}
</script>
