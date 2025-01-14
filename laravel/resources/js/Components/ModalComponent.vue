<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center" @click="closeModal" v-if="isVisible">
        <div class="modal-window-container bg-white p-6 rounded-lg shadow-lg w-full max-w-md pointer-events-auto relative" @click.stop>
            <header class="text-xl font-bold mb-4">
                <button @click="closeModal" class="absolute top-1 right-3 text-gray-500 hover:text-gray-700">
                    &times;
                </button>
                <slot name="header"></slot>
            </header>
            <main class="modal-body">
                <slot name="body"></slot>
            </main>
            <footer class="flex justify-end gap-2" v-if="$slots.footer">
                <slot name="footer"></slot>
                <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Close</button>
            </footer>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'ModalComponent',
    props: {
        isVisible: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        closeModal() {
            this.$emit('close');
        }
    }
})
</script>
<style scoped>
.modal-window-container {
    width: 100%;
    max-width: 1000px;
    max-height: 1000px;
    overflow-y: auto;
}
</style>