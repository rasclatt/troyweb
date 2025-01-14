<template>
    <ul :class="className">
        <li v-for="book in books" :key="book.id">
            <div class="my-books-thumbs text-center border border-gray-200 p-4 rounded-lg">
                <div class="flex justify-center">
                    <img :src="book.cover_image" alt="Book cover" class="w-[100px] h-auto object-cover">
                </div>
                <div class="text-center pt-3">
                    <h6 class="font-semibold">{{ book.title }}</h6>
                    <p class="text-sm">by {{ book.author }}</p>
                </div>
                <div class="align-middle">
                    <button @click="to(book.id)" class=" mt-4 border border-black text-black px-2 py-1 rounded-lg hover:bg-gray-600 transition duration-300 uppercase text-xs">Details</button>
                </div>
            </div>
        </li>
    </ul>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

export default defineComponent({
    name: 'BookGrid',
    props: {
        books: {
            type: Array,
            required: true
        },
        className: {
            type: String,
            default: 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-4'
        }
    },
    setup() {
        const to = (id: string) => window.location.href = route('books.details', { id });
        return {
            to
        };
    }
})
</script>

<style scoped>
.my-books-thumbs {
    display: grid;
    grid-template-rows: auto 1fr auto;
    height: 100%;
}
</style>