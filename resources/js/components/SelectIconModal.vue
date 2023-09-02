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

            <ModalContent class="px-8">
                <nav>
                    <ul class="flex">
                        <li v-for="set in sets" :key="set.name">
                            <button>{{ set.display }}</button>
                        </li>
                    </ul>
                </nav>

                <div class="py-6">
                    <Loader
                        v-if="isFetching"
                        width="32"
                    />

                    <ul
                        v-else-if="icons.length > 0"
                        class="grid grid-cols-4 gap-6"
                    >
                        <li v-for="icon in icons" :key="icon.name" class="w-full">
                            <button
                                type="button"
                                class="block p-4 w-full border-2 rounded"
                                :class="selectedIcon && selectedIcon.name === icon.name ? 'border-primary-500 bg-primary-50' : 'border-transparent'"
                                @click="selectIcon(icon)"
                            >
                            <span class="flex items-center justify-center">
                                <span v-html="icon.contents" class="inline-block w-10 h-10" />
                            </span>

                                <span class="mt-3 block text-center">{{ icon.name }}</span>
                            </button>
                        </li>
                    </ul>

                    <div v-else>
                        No icons found.
                    </div>
                </div>

                <!-- @todo add button to select icon from local computer -->
                <textarea
                    v-if="false"
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
import { ref, onMounted } from 'vue'

const props = defineProps(['sets'])

const emits = defineEmits(['close', 'select'])

const currentSet = ref(0)

const icons = ref([])

const isFetching = ref(false)

const selectedIcon = ref()

const rawSvgIcon = ref()

const fetchIcons = async () => {
    isFetching.value = true

    const response = await Nova.request().get(`/nova-vendor/iconsets/${props.sets[currentSet.value].name}/icons`)

    isFetching.value = false

    return response.data
}

const submit = () => {
    emits('select', {
        set: props.sets[0],
        icon: rawSvgIcon
    })
}

const selectIcon = (icon) => {
    selectedIcon.value = icon
}

onMounted(async () => {
    icons.value = await fetchIcons()
})
</script>
