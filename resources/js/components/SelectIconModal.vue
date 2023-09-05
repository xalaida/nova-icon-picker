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
                <div class="border-t border-b px-8 py-3 flex items-center justify-between">
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

                <div
                    class="py-6 px-8 overflow-x-hidden overflow-y-auto"
                    style="max-height: 600px"
                >
                    <LoadingView
                        :loading="fetching"
                    >
                        <ul
                            v-if="icons.length > 0"
                            class="grid gap-2"
                            style="grid-template-columns: repeat(12, minmax(0, 1fr));"
                        >
                            <li v-for="icon in filteredIcons" :key="icon.name" class="w-full">
                                <button
                                    type="button"
                                    class="block p-2 w-full border-2 rounded"
                                    :class="currentIcon === icon.name ? 'border-primary-500 bg-primary-50' : 'border-transparent'"
                                    @click="selectIcon(icon)"
                                >
                                    <span class="flex items-center justify-center">
                                        <span v-html="icon.contents" class="inline-block w-10 h-10" />
                                    </span>
                                </button>
                            </li>
                        </ul>

                        <div v-else>
                            No icons found.
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

const currentIconset = ref(props.currentIconset ?? Object.keys(props.iconsets)[0])

const iconsetOptions = computed(() => Object.keys(props.iconsets).map((iconset) => ({
    label: props.iconsets[iconset].display,
    value: iconset,
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

    // @todo handle exception
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
