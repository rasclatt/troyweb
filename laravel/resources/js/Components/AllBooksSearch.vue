<template>
    <div>
        <div class="flex justify-between gap-4">
            <input type="text" v-model="searchQuery" placeholder="Search books..." class="text-black" />
            <div class="flex justify-center">
                <button @click="searchBooks" class="m-4"><font-awesome-icon icon="magnifying-glass" /></button>
                <button v-if="searchQuery" @click="clearSearch" class="m-4"><font-awesome-icon icon="rotate-left" /></button>
            </div>
        </div>
        <BookGrid :books="books || []" />
    </div>
</template>

<script lang="ts">
import { IBook } from '@/Interfaces/IBooks';
import { defineComponent, onMounted, ref } from 'vue';
import BookGrid from '@/Components/BookGrid.vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default defineComponent({
    name: 'AllBooksSearch',
    components: {
        BookGrid
    },
    setup() {
        const toast = useToast();
        const books = ref<IBook[]>([]);
        const searchQuery = ref<string>('');

        const clearSearch = () => {
            searchQuery.value = '';
            fetchBooks();
        };

        const fetchBooks = async () => {
            try {
                const response = await axios.get(`/api/books/search${searchQuery.value ? `?search=${searchQuery.value}` : ''}`);
                books.value = response.data.data;
            } catch (error: any) {
                toast.error(`Error fetching books: ${error.message}`);
            }
        }

        const searchBooks = () => {
            fetchBooks();
        }

        onMounted(() => {
            fetchBooks();
        });

        return {
            books,
            searchQuery,
            clearSearch,
            fetchBooks,
            searchBooks,
        };
    }
});
</script>

<style scoped>
input {
    margin-bottom: 20px;
    padding: 10px;
    width: 100%;
    box-sizing: border-box;
}
ul {
    list-style-type: none;
    padding: 0;
}
li {
    padding: 10px;
    border-bottom: 1px solid #ccc;
}
</style>