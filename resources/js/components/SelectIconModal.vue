<template>
    <Modal
        show
        size="5xl"
        role="alertdialog"
        @close-via-escape="$emit('close')"
    >
        <div class="bg-white dark:bg-gray-800">
            <ModalHeader class="flex items-center">
                Select icon
            </ModalHeader>

            <div>
                <div class="border-t border-b px-6 py-3 flex items-center justify-between">
                    <div class="-mx-2 w-1/2 px-2">
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Search icons..."
                            class="w-full form-control form-input form-input-bordered"
                        />
                    </div>

                    <div class="-mx-2 w-1/2 px-2">
                        <SelectControl
                            :options="iconsetOptions"
                            :selected="currentIconset"
                            @change="changeIconset"
                        />
                    </div>
                </div>

                <div class="py-6 px-6 overflow-x-hidden overflow-y-auto" style="max-height: 600px">
                    <LoadingView :loading="fetching">
                        <ul v-if="icons.length > 0" class="grid grid-cols-4 md:grid-cols-12 gap-2">
                            <li v-for="icon in filteredIcons" :key="icon.name" class="w-full">
                                <button
                                    type="button"
                                    class="block p-2 w-full border-2 rounded"
                                    :class="currentIcon === icon.name ? 'border-primary-500 bg-primary-50' : 'border-transparent'"
                                    @click="selectIcon(icon)"
                                >
                                    <span class="flex items-center justify-center">
                                        <span v-html="icon.contents" class="inline-block w-10 h-10"/>
                                    </span>
                                </button>
                            </li>
                        </ul>

                        <div v-else class="text-center py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>

                            <p class="mt-3">No icons found.</p>
                        </div>
                    </LoadingView>
                </div>

                <!-- @todo add button to select icon from local computer -->
                <!-- <textarea-->
                <!--     v-if="false"-->
                <!--     type="text"-->
                <!--     class="block w-full form-control form-input form-input-bordered py-3 h-auto"-->
                <!--     rows="5"-->
                <!--     :placeholder="__('Paste SVG...')"-->
                <!--     v-model="rawSvgIcon"-->
                <!-- />-->
            </div>

            <ModalFooter>
                <div class="ml-auto space-x-3">
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
import { ref, computed, onMounted } from 'vue'

const props = defineProps(['resourceName', 'attribute', 'iconsets', 'currentIconset', 'currentIcon'])

const emits = defineEmits(['close', 'select'])

const currentIconset = ref(props.currentIconset ?? props.iconsets[0].name)

const iconsetOptions = computed(() => props.iconsets.map((iconset) => ({
    value: iconset.name,
    label: iconset.display,
})))

const changeIconset = async (iconset) => {
    currentIconset.value = iconset

    await fetchIcons()
}

const fetching = ref(false)

const icons = ref([])

const search = ref('')

const filteredIcons = computed(() => icons.value.filter((icon) => icon.name.includes(search.value)))

const fetchIcons = async () => {
    fetching.value = true

    const response = await Nova.request().get(`/nova-vendor/icon-picker/${props.resourceName}/fields/${props.attribute}/iconsets/${currentIconset.value}`)

    fetching.value = false

    icons.value = response.data
}

const selectIcon = (icon) => {
    emits('select', currentIconset.value, icon.name, icon.contents)
}

onMounted(async () => {
    await fetchIcons()
})
</script>
