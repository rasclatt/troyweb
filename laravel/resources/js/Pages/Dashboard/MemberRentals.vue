<template>
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">My Rentals</h3>
        <p v-if="rentals.length === 0" class="text-gray-600">You have no rentals</p>
        <div v-else>
            <BookGrid :books="rentals" />
        </div>
    </div>
</template>
<script lang="ts">
import { IBook } from '@/Interfaces/IBooks';
import axios from 'axios';
import { defineComponent, onMounted, ref } from 'vue';
import { route } from 'ziggy-js';
import BookGrid from '@/Components/BookGrid.vue';

export default defineComponent({
    name: 'MemberRentals',
    components: {
        BookGrid
    },
    setup() {
        const rentals = ref<IBook[]>([]);
        const getRentals = async () => {
            await axios.get('/api/my-rentals')
                .then(r => {
                    rentals.value = r.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        };
        onMounted(() => {
            getRentals();
        });
        return {
            rentals,
            getRentals,
            route
        };
    },
});
</script>
<style scoped>
.my-rentals-thumbs {
    display: grid;
    grid-template-rows: auto 1fr auto;
    height: 100%;
}
</style>