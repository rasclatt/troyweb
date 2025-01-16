<template>
    <div class="p-4">
        <h2 :class="class" :style="{textAlign: centerHeader? 'center' : 'left'}">Some Random Book Choices <span class="text-orange-500 font-semibold">Just For You!</span></h2>
        <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
            <li v-for="book in randomBooks" :key="book.id" class="border mb-4 rounded-md shadow-md  h-[500px] overflow-hidden relative zoom-in-on-hover">
                <div class="rounded-md overflow-hidden" :style="{ backgroundImage: `url(${book.cover_image})`, backgroundSize: 'cover', backgroundPosition: 'center', position: 'absolute', top: 0, right: 0, bottom: 0, left: 0 }"></div>
                <div class="grid h-full" style="grid-template-rows: 300px 1fr auto; background-color: transparent; z-index: 2; position: relative;">
                    <div></div>
                    <div class="p-4 row-span-1 bg-gray-200 bg-opacity-50 backdrop-blur">
                        <h6 class="font-semibold">Title: {{ book.title }}</h6>
                        <p class="text-sm">Author: {{ book.author }}</p>
                        <div v-if="book?.average_rating || 0 > 0" class="flex items">
                          <StarRatings :rating="book.average_rating || 0" />
                        </div>
                    </div>
                    <div class="p-4 row-span-1 bg-gray-200 bg-opacity-50">
                        <div class="flex justify-center">
                            <!-- <button v-if="isLoggedIn" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition duration-300">Borrow</button> -->
                            <button class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300" v-on:click="viewDetails(book.id)">Details</button>
                        </div>
                    </div>
                </div>
                
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import { IBook } from '@/Interfaces/IBooks';
import { defineComponent, onMounted, ref } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';
import StarRatings from './StarRatings.vue';

export default defineComponent({
  name: 'RandomBookSuggestions',
  components: {
    StarRatings
  },
  props: {
    randomBooks: {
      type: Array as () => Array<IBook>,
      required: false
    },
    centerHeader: {
      type: Boolean as () => Boolean,
      required: false,
      default: false
    },
    class: {
      type: String as () => String,
      required: false,
      default: 'text-2xl mb-4'
    }
  },
  setup() {
    const randomBooks = ref<IBook[]>([]);
    const isLoggedIn = ref<boolean>(false);
    const viewDetails = (id: string) => {
      try {
        window.location.href = route('books.details', { id });
      } catch (error) {
        console.error('Error navigating to book details:', error);
      }
    };
    onMounted(() => {
      axios.get('/api/random-books').then(response => {
        if (response.data.success) {
          randomBooks.value = response.data.data;
          isLoggedIn.value = response.data.loggedIn;
        }
      }).catch(error => {
        console.error('Error fetching random books:', error);
      });
    });
    return {
      randomBooks,
      isLoggedIn,
      viewDetails
    };
  }
});
</script>
<style scoped>
  .zoom-in-on-hover:hover {
    transition: transform 0.3s ease-in-out;
    transform: scale(1.05);
  }
</style>