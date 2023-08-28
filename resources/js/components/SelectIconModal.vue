<template>
    <Modal
        show
        size="2xl"
        role="alertdialog"
        @close-via-escape="$emit('close')"
    >
        <div class="bg-white dark:bg-gray-800">
            <ModalHeader class="flex items-center">
                Select icon
            </ModalHeader>

            <ModalContent class="px-8 divide-y divide-gray-100 dark:divide-gray-800 -mx-3">
                <ul v-if="hasOptions">
                    <li
                        v-for="(component, type) in [1, 2, 3]"
                        :key="type"
                    >
                        <button
                            type="button"
                            class="w-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300 py-2 px-3 no-underline font-normal text-left"
                            @click="emits('selected', type)"
                        >
                            {{ component }}
                        </button>
                    </li>
                </ul>

                <div v-else>
                    There are no registered components.
                </div>
            </ModalContent>

            <ModalFooter>
                <div class="ml-auto">
                    <BasicButton
                        type="button"
                        @click="$emit('close')"
                    >
                        {{ __('Cancel') }}
                    </BasicButton>
                </div>
            </ModalFooter>
        </div>
    </Modal>
</template>

<script setup>
import { computed } from 'vue'

// const props = defineProps({
//     options: { type: Object, required: true },
// })

const emits = defineEmits(['close', 'selected'])

const hasOptions = computed(() => true)
</script>
