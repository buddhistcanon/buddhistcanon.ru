<script setup>
import Button from "@/Components/Button.vue";
import {ref, watch, onMounted, onUnmounted} from 'vue'

const props = defineProps({
    display: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: 'Modal title'
    },
    textOk: {
        type: String,
        default: 'Ok'
    },
    handleOk: {
        type: Function,
        default: () => {
        }
    },
    handleCancel: {
        type: Function,
        default: () => {
        }
    },
    textCancel: {
        type: String,
        default: 'Close'
    }
})

const emit = defineEmits(['update:isShowModal'])

const scrollbarWidth = ref(0)

const handleOk = () => {
    props.handleOk()
    emit('update:isShowModal', false)
}

const handleCancel = () => {
    props.handleCancel()
    emit('update:isShowModal', false)
}

const closeModalOnOutsideClick = (event) => {
    if (event.target === event.currentTarget) {
        handleCancel()
    }
}

const calculateScrollbarWidth = () => {
    const scrollDiv = document.createElement('div')
    scrollDiv.style.width = '100px'
    scrollDiv.style.height = '100px'
    scrollDiv.style.overflow = 'scroll'
    scrollDiv.style.position = 'absolute'
    scrollDiv.style.top = '-9999px'
    document.body.appendChild(scrollDiv)
    scrollbarWidth.value = scrollDiv.offsetWidth - scrollDiv.clientWidth
    document.body.removeChild(scrollDiv)
}

watch(() => props.display, (newValue) => {
    if (newValue) {
        // document.body.style.overflow = 'hidden'
    } else {
        // document.body.style.overflow = ''
    }
})

onMounted(() => {
    calculateScrollbarWidth()
    window.addEventListener('resize', calculateScrollbarWidth)
})

onUnmounted(() => {
    window.removeEventListener('resize', calculateScrollbarWidth)
    document.body.style.overflow = ''
})

</script>

<template>
    <Teleport to="body">
        <div
            v-if="display"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            :style="{ paddingRight: scrollbarWidth + 'px' }"
            @click="closeModalOnOutsideClick"
        >
            <div class="sm:max-w-lg sm:w-full sm:mx-auto m-3" @click.stop>
                <div
                    class="max-w-xl flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto ">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                        <slot name="#header">
                            <h3 id="hs-basic-modal-label" class="font-bold text-gray-800 dark:text-white">
                                {{ props.title }}
                            </h3>
                        </slot>
                        <button @click="handleCancel" type="button"
                                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                                aria-label="Close" data-hs-overlay="#hs-basic-modal">
                            <span class="sr-only">Close</span>
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <slot></slot>
                    </div>
                    <slot name="footer">
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                            <button v-if="props.textCancel" type="button" @click="handleCancel"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                    data-hs-overlay="#hs-basic-modal">
                                {{ props.textCancel }}
                            </button>
                            <button v-if="props.textOk" type="button" @click="handleOk"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                {{ props.textOk }}
                            </button>
                        </div>
                    </slot>

                </div>
            </div>
        </div>
    </Teleport>
</template>
