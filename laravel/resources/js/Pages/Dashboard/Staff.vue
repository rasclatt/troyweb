<template>
    <div>
        <div class="flex flex-col md:flex-row justify-between mb-8 gap-4 text-center md:text-left">
            <div>
                <h1 class="text-2xl font-bold uppercase text-orange-500">Inventory Administration</h1>
                <p>Manage books in the system.</p>
            </div>
            <div>
                <button class="border border-orange-500 text-orange-500 rounded-lg px-4 py-2 hover:bg-orange-500 hover:text-white transition duration-300" @click="openClearModal">Add Inventory</button>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Availability</th>
                    <th>Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="book in books" :key="book.id">
                    <td>{{ book.title }}</td>
                    <td>{{ book.author }}</td>
                    <td>{{ book.publisher }}</td>
                    <td class="text-center">
                        <font-awesome-icon v-if="book.action === 'signed_in' || !book.action" icon="circle-check" />
                        <font-awesome-icon v-if="!(book.action === 'signed_in' || !book.action)" icon="ban" />
                    </td>
                    <td class="text-center">{{ book.action === 'signed_out' ? toDate(book.action_date) : '' }}</td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 hover:bg-gray-300 transition duration-300" v-if="book.action_date && book.action === 'signed_out'" @click="markAsReturned(book.id)"><font-awesome-icon icon="book" /></button>
                            <button class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 hover:bg-gray-300 transition duration-300" v-if="book.id" @click="editBook(book.id)"><font-awesome-icon icon="pen-to-square" /></button>
                        </div>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <ModalComponent
        :isVisible="modalActive"
        @close="closeModal"
    >
        <template #body>
            <form @submit.prevent="addBook" enctype="multipart/form-data">
                <div class="books-details-wrapper gap-4">
                    <div>
                        <img v-if="form.cover_image" :src="form.cover_image" alt="Book Cover" class="image-cover border shadow-lg">
                    </div>
                    <div class="details-content">
                        <div class="mb-4">
                            <div class="flex border rounded-lg border-gray-300 bg-gray-200 my-4 overflow-hidden">
                                <button
                                    v-for="tab in tabs"
                                    :key="tab"
                                    @click="activeTab = tab"
                                    :class="{'bg-orange-500 text-white': activeTab === tab}"
                                    class="m-0 uppercase px-4 py-2 text-sm font-semibold text-gray-500 hover:bg-orange-500 hover:text-white transition duration-300"
                                    type="button"
                                >
                                    {{ tab }}
                                </button>
                            </div>
                            <div v-if="activeTab === 'Details'">
                                <div class="mb-4">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" id="title" v-model="form.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                                    <input type="text" id="author" v-model="form.author" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="description" v-model="form.description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700">Upload Cover Image</label>
                                    <input type="file" ref="fileInput" id="image" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            <div v-if="activeTab === 'Additional Info'">
                                <div class="mb-4">
                                    <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
                                    <input type="text" id="publisher" v-model="form.publisher" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                    <select id="category" v-model="form.category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="fiction">Fiction</option>
                                        <option value="non_fiction">Non-Fiction</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                                    <input type="text" id="isbn" v-model="form.isbn" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="page_count" class="block text-sm font-medium text-gray-700">Page Count</label>
                                    <input type="number" id="page_count" v-model="form.page_count" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="publication_date" class="block text-sm font-medium text-gray-700">Publication Date</label>
                                    <input type="date" id="publication_date" v-model="form.publication_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4 flex items-start gap-2">
                                    <label for="delete_at" class="block text-sm font-medium text-gray-700">Delete Book</label>
                                    <input type="checkbox" id="delete" :checked="!!form.deleted" v-model="form.delete" class="mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-2">
                    <div class="flex justify-end">
                        <button type="submit" class="border border-orange-500 text-orange-500 rounded-lg px-4 py-2 hover:bg-orange-500 hover:text-white transition duration-300">{{ form.id? 'Update' : 'Add' }} Book</button>
                    </div>
                </div>
            </form>
        </template>
    </ModalComponent>
</template>

<script lang="ts">
import ModalComponent from '@/Components/ModalComponent.vue';
import { toDate } from '@/Helpers/DateTime';
import axios from 'axios';
import { defineComponent, onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';

export default defineComponent({
    name: 'StaffDashboard',
    components: {
        ModalComponent
    },
    setup() {
        const toast = useToast();
        const form = ref<{[k: string]: any}>({});
        const books = ref<any[]>([]);
        const modalActive = ref<boolean>(false);
        const fileInput = ref<HTMLInputElement | null>(null);
        const activeTab = ref<string>('Details');
        const tabs = ['Details', 'Additional Info'];
        const setForm = (key: string, value: any) => {
            form.value[key] = value;
        };

        const openClearModal = () => {
            form.value = {};
            showModal();
        }

        const editBook = (id: string) => {
            fetchBooks(id, () => {
                showModal();
            });
        };

        const closeModal = () => {
            modalActive.value = false;
        };

        const showModal = () => {
            modalActive.value = true;
        };

        const fetchBooks = (id?: string, func?: (resp?: any) => void) => {
            axios.get(`/api/books${id ? `/${id}` : ''}`)
                .then(response => {
                    if(id) 
                        form.value = response.data.data;
                    else
                        books.value = response.data.data;
                    if(func)
                        func(response?.data);
                })
                .catch(error => {
                    console.error('Error fetching books:', error);
                });
        };

        const addBook = () => {
            const formData = new FormData();
            for (const key in form.value) {
                formData.append(key, form.value[key]);
            }
            if (fileInput.value && fileInput.value.files && fileInput.value.files.length > 0) {
                formData.append('file', fileInput.value.files[0]);
            }
            const isUpdate = !!form.value.id;
            axios.post(`/api/books${form.value.id? `/${form.value.id}`: ''}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((r) => {
                if(r.data.success) {
                    fetchBooks();
                    if(!isUpdate || form.value.delete_at) {   
                        closeModal();
                    } else {
                       form.value = r.data.data; 
                    }
                    toast.success("Book saved successfully");
                } else {
                    toast.error(r.data.message || r.data.error);
                }
            })
            .catch(error => {
                console.error('Error adding book:', error);
            });
        };

        const markAsReturned = (bookId: string) => {
            axios.patch(`/api/rental/${bookId}`)
                .then(() => fetchBooks())
                .catch(error => {
                    console.error('Error marking book as returned:', error);
                });
        };

        onMounted(() => {
            fetchBooks();
        });

        return {
            form,
            books,
            modalActive,
            activeTab,
            tabs,
            fileInput,
            toDate,
            fetchBooks,
            markAsReturned,
            showModal,
            closeModal,
            setForm,
            addBook,
            editBook,
            openClearModal,
        };
    }
});
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
}

th {
    background-color: #f2f2f2;
}
.books-details-wrapper {
    display: grid;
    grid-template: auto / auto 1fr;
}
.image-cover {
    max-width: 300px;
}
</style>