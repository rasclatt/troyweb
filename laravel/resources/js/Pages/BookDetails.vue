<template>
    <AuthenticatedLayout>
        <template #default>
            <div class="col-count-3 offset mt-[4em]">
                <div class="start2 py-4">
                    <div class="main-book-view">
                        <div class="book-image hide-mobile">
                            <img :src="book.cover_image" alt="Book Cover" />
                        </div>
                        <div class="book-details">
                            <div class="border-b border-gray-300 pb-4">
                                <h1 class="text-4xl text-orange-800">{{ book.title }}</h1>
                                <p>by {{ book.author }}</p>
                                <p v-if="book.average_rating" >{{ book.average_rating }} <font-awesome-icon icon="star" class="text-orange-500" /> Member Rating</p>

                                <div>
                                    <button v-if="!borrowing && (!book.action || book.action === 'signed_in')" class="text-white mt-4 px-3 py-2 rounded-lg transition duration-300" :class="{'hover:bg-orange-600 bg-orange-500': $page.props.auth.user, 'bg-gray-300 hover:bg-gray-200': !$page.props.auth.user }" :disabled="!$page.props.auth.user" type="button" @click="borrowBook">One-Click Borrow</button>
                                    <div v-if="borrowing" class=" mt-4">
                                        <font-awesome-icon icon="spinner" class="animate-spin text-4xl text-orange-500" />
                                    </div>

                                    <div v-if="!book.action || book.action === 'signed_in'" class="full-width mt-4"><font-awesome-icon icon="circle-check" />&nbsp;Available. <span v-if="!$page.props.auth.user"><a href="/login" class="text-orange-500">Login</a> to borrow this book.</span></div>
                                    <div v-if="book.action === 'signed_out'" class="full-width mt-4"><font-awesome-icon icon="triangle-exclamation" />&nbsp;All copies are currently signed out.<br /><span class="font-semibold italic text-orange-500">The item is due to be returned on {{toDate(book.action_date)}}.</span></div>
                                </div>
                            </div>
                            <h1 class="text-2xl text-orange-800 mt-6">Overview</h1>
                            <p class="text-lg">{{ book.description }}</p>
                        </div>
                        <div class="book-image hide-tablet">
                            <img :src="book.cover_image" alt="Book Cover" />
                        </div>
                    </div>
                    <div v-if="ready" class="book-reviews mt-4">
                         <h1 class="text-2xl text-orange-800 mb-4">What do others think?</h1>
                        <div v-if="ratings.length === 0" class="text-lg">
                            No reviews yet. <button v-if="$page.props.auth.user" v-on:click="openModal" class="border rounded-md px-3 py-1" :class="{'border-orange-500 text-orange-500': $page.props.auth.user, 'text-gray-300 border-gray-300': !$page.props.auth.user, }" >Add a review?</button>
                        </div>
                        <div v-else>
                            <div v-if="!ratedByMe && $page.props.auth.user" class="text-md mb-4">
                                <button v-on:click="openModal" class="border rounded-md px-3 py-1" :class="{'border-orange-500 text-orange-500': $page.props.auth.user, 'text-gray-300 border-gray-300': !$page.props.auth.user, }" :disabled="!$page.props.auth.user" >Add a review?</button>
                            </div>
                            <div v-for="rating in ratings" :key="rating.id" class="border-t border-gray-300 py-2">
                                <div class="flex justify-stretch">
                                    <div>
                                        <p class="text-xs ">
                                            <StarRatings :rating="rating.rating" /> - {{ new Date(rating.created_at).toLocaleDateString() }}</p>
                                        
                                        <h1 class="text-xl">{{ rating.title }}</h1>
                                        <div class="border-top pt-3 mt-3">
                                            <p class="font-light">{{ rating.description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center mt-4">
                        <font-awesome-icon icon="spinner" class="animate-spin text-4xl text-orange-500" />
                    </div>
                </div>
                <div class="start2">
                    <AllBooksSearch />
                </div>
            </div>
            <ModalComponent
                :isVisible="modalVisible"
                @close="closeModal"
            >
                <template #header>
                    <h1 class="text-xl text-orange-800">Add a review for "{{ book.title }}"</h1>
                </template>
                <template #body>
                    <form @submit.prevent="submitReview">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" v-model="review.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required maxlength="30" />
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" v-model="review.description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" rows="4"  maxlength="255" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <select id="rating" v-model="review.rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                                <option value="" disabled>Select a rating</option>
                                <option v-for="star in 5" :key="star" :value="star">{{ star }} Star{{ star > 1 ? 's' : '' }}</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 transition duration-300">Submit</button>
                        </div>
                    </form>
                </template>
            </ModalComponent>
        </template>
    </AuthenticatedLayout>
</template>

<script lang="ts">
import { defineComponent, computed, onErrorCaptured, ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { toDate } from '@/Helpers/DateTime';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalComponent from '@/Components/ModalComponent.vue';
import axios from 'axios';
import AllBooksSearch from '@/Components/AllBooksSearch.vue';
import StarRatings from '@/Components/StarRatings.vue';

export default defineComponent({
    name: 'BookDetails',
    components: {
        AuthenticatedLayout,
        AllBooksSearch,
        ModalComponent,
        StarRatings,
    },
  setup() {
    const { props } = usePage<any>();
    const book = computed(() => props.data.original.data);
    const ratings = ref<any>([]);
    const review = ref<any>({});
    const ratedByMe = ref<boolean>(true);
    const toast = useToast();
    const modalVisible = ref<boolean>(false);
    const ready = ref<boolean>(false);
    const borrowing = ref<boolean>(false);

    const getLastPathSegment = (): string | undefined => {
        const url: string = window.location.href;
        const segments = url.split('/').filter(segment => segment.length > 0);
        return segments.pop();
    };

    const id: string | undefined = getLastPathSegment();

    const openModal = () => {
        modalVisible.value = true;
    };

    const closeModal = () => {
        modalVisible.value = false;
    };

    const borrowBook = async () => {
        try {
            borrowing.value = true;
            const response = await axios.post(`/api/rental/${id}`);
            if(!response.data.success) {
                toast.error('Error renting book');
            } else {
                toast.success('Your book has been rented!');
                window.location.reload();
            }
        } catch (error: any) {
            toast.error(`Error renting book: ${error.message}`);
            return [];
        }
    }

    const submitReview = async () => {
        try {
            // Set the book id
            review.value.bid = id;
            const response = await axios.post(`/api/book-reviews/${id}`, review.value);
            if(!response.data.success) {
                toast.error('Error saving book ratings');
            } else {
                toast.success('Thank you for your review!');
                getBookRatings();
                closeModal();
                
            }
        } catch (error: any) {
            toast.error(`Error saving book ratings: ${error.message}`);
            return [];
        }
    }

    onErrorCaptured((err, instance, info) => {
        console.error('Error captured:', err);
        console.error('Component instance:', instance);
        console.error('Error info:', info);
        return false; // Prevent the error from propagating further
    });

    const getBookRatings = async () => {
        try {
            const response = await axios.get(`/api/book-reviews/${id}`);
            if(!response.data.success) {
                toast.error('Error fetching book ratings');
            } else {
                ratings.value = response.data.data;
                ratedByMe.value = response.data.rated;
            }
            ready.value = true;
        } catch (error: any) {
            toast.error(`Error fetching book ratings: ${error.message}`);
            return [];
        }
    };

    onMounted(() => {
        getBookRatings();
    });

    console.log('Book:', book.value);
    
    return {
        book,
        ratings,
        modalVisible,
        review,
        ratedByMe,
        ready,
        borrowing,
        openModal,
        closeModal,
        submitReview,
        borrowBook,
        toDate,
    };
  }
});
</script>

<style scoped lang="css">
    .main-book-view {
        display: grid;
        grid-template: 1fr / auto 1fr;
        gap: 2rem;
    }
    .hide-tablet {
        display: none;
    }
    .book-reviews {
        background-color: #FFF;
        border: 1px solid #EBEBEB;
        padding: 1em;
        margin: 1em 0;
    }
    @media all and (max-width: calc(648px + 2rem)) {
        .main-book-view {
            grid-template: 1fr / 1fr;
            gap: 2rem;
        }
        .hide-mobile {
            display: none;
        }
        .hide-tablet {
            display: block;
        }
    }
    .book-image img {
        width: 400px;
        height: auto;
    }
</style>