<template>
    <div class="flex justify-start gap-4">
        <div class="mb-4 flex justify-start gap-4">
            <label for="filter" class="block text-sm font-medium text-gray-700 flex py-2">Filter&nbsp;by:</label>
            <div>
                <select id="filter" v-model="selectedFilter" @change="applyFilter" class="mt-1 block w-auto pl-3 pr-10 p-1 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm rounded-md my-4">
                    <option value="">Select filter</option>
                    <option value="author">Author</option>
                    <option value="title">Title</option>
                    <option value="average_rating">Average Rating</option>
                </select>
            </div>
        </div>
        <div v-if="books.length > 0" class="flex justify-center">
            <div>
                <button @click="reverseOrder" class="mb-4 text-xs border border-gray-300 rounded-lg px-2 py-1 my-2 text-sm hover:bg-gray-200 transition duration-300">
                    <font-awesome-icon :icon="!order ? 'left-long' : 'right-long'" />
                    <span>&nbsp;ORDER</span>
                </button>
            </div>
        </div> 
    </div>
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
import { IBook } from '@/Interfaces/IBooks';
import { defineComponent, ref, watch } from 'vue';
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
    setup(props) {
        const to = (id: string) => window.location.href = route('books.details', { id });

        const selectedFilter = ref('');
        const order = ref(false);
        const books = ref<IBook[]>([]);

        watch((): IBook[] => props.books as IBook[], (newBooks: IBook[]) => {
            books.value = newBooks;
        }, { immediate: true });

        const applyFilter = () => {
            if (selectedFilter.value === 'author') {
                books.value.sort((a: IBook, b: IBook) => a.author.localeCompare(b.author));
            } else if (selectedFilter.value === 'title') {
                books.value.sort((a: IBook, b: IBook) => a.title.localeCompare(b.title));
            } else if (selectedFilter.value === 'average_rating') {
                books.value.sort((a: IBook, b: IBook) => (b.average_rating || 0) - (a.average_rating || 0));
            }
        };

        const reverseOrder = () => {
            order.value = !order.value;
            books.value.reverse();
        };

        return {
            to,
            reverseOrder,
            applyFilter,
            selectedFilter,
            order,
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