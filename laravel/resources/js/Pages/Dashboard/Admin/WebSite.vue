<template>
    <div class="mt-4">
        <h1 class="text-lg font-bold uppercase text-orange-500">Website Settings</h1>
        <p>Manage website settings including hero image, header text, book random count.</p>
        
        <form @submit.prevent="onSubmitHeroImage" class="mt-4 flex justify-between" enctype="multipart/form-data">
            <input type="hidden" name="_heroimage_token" value="{{ csrf_token() }}" />
            <input v-if="heroText.content" type="hidden" name="id" v-model="heroText.content" />
            <div class="flex flex-col">
                <label for="file" class="text-sm uppercase mb-2">Hero Image:</label>
                <input type="file" id="hero-image" />
            </div>
            <div class="flex items-center">
                <button type="submit" class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 text-xs hover:bg-gray-300 transition duration-300">Save</button>
            </div>
        </form>

        <form @submit.prevent="onSubmitHeroText" class="mt-4 flex justify-between">
            <input type="hidden" name="_herotext_token" value="{{ csrf_token() }}" />
            <input v-if="heroText.id" type="hidden" name="id" v-model="heroText.id" />
            <div>
                <label for="headerText" class="text-sm uppercase">Header Text:</label>
                <input type="text" id="headerText" v-model="heroText.content" class="w-full" />
            </div>
            <div class="flex items-center">
                <button type="submit" class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 text-xs hover:bg-gray-300 transition duration-300">Save</button>
            </div>
        </form>

        <form @submit.prevent="onSubmitBookCount" class="mt-4 flex justify-between">
            <input type="hidden" name="_bookcount_token" value="{{ csrf_token() }}" />
            <input v-if="bookCount.id" type="hidden" name="id" v-model="bookCount.id" />
            <div>
                <label for="numberValue" class="text-sm uppercase">Random Books Display Count:</label>
                <input type="number" id="numberValue" v-model.number="bookCount.content" class="w-full" />
            </div>
            <div class="flex items-center">
                <button type="submit" class="border border-gray-300 text-gray-700 rounded-lg px-2 py-1 text-xs hover:bg-gray-300 transition duration-300">Save</button>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';

interface ISiteSettings
{
    id: number | null;
    type: string;
    content: string;
    category: string;
    shortcode: string;
}

const initSiteSettings: ISiteSettings = {
    id: null,
    type: '',
    content: '',
    category: '',
    shortcode: '',
};

export default {
    name: 'WebSite',
    setup() {
        const heroText = ref<ISiteSettings>(initSiteSettings);
        const bookCount = ref<ISiteSettings>(initSiteSettings);
        const heroImage = ref<ISiteSettings>(initSiteSettings);
        const toast = useToast();
        const getSiteSettings = () => {
            axios.get('/api/site-settings')
                .then(r => {
                    if(r.data.success) {
                        heroText.value = r.data.data?.heroTitle || {};
                        bookCount.value = r.data.data?.randomBookCount || {content: 0};
                        heroImage.value = r.data.data?.heroImage || {};
                    }
                })
                .catch(error => {
                    console.error('Error fetching site settings:', error);
                });
        };

        const onSubmitHeroImage = () => {
            const formData = new FormData();
            const fileInput = document.getElementById('hero-image') as HTMLInputElement;
            const tokenElement = document.querySelector('input[name="_token"]') as HTMLInputElement;
            if(fileInput.files)
                formData.append('file', fileInput.files[0]);
            if (tokenElement) {
                formData.append('_token', tokenElement.value);
            }
            for (const key in heroImage.value) {
                const k: string = heroImage.value[key as keyof ISiteSettings] as string;
                formData.append(key, k);
            }
            axios.post('/api/site-settings', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if(response.data.success) {
                    toast.success('Hero image uploaded successfully');
                    heroImage.value = response.data.data;
                    fileInput.value = '';
                } else {
                    toast.error('Error uploading hero image');
                }
            })
            .catch(error => {
                toast.error('Error uploading hero image:', error);
            });
        };

        const onSubmitHeroText = () => {
            axios.post('/api/site-settings', heroText.value)
                .then(response => {
                    if(response.data.success) {
                        toast.success('Hero text updated successfully');
                        heroText.value = response.data.data;
                    } else {
                        toast.error('Error updating hero text');
                    }
                })
                .catch(error => {
                    toast.error('Error updating hero text:', error);
                });
        };

        const onSubmitBookCount = () => {
            bookCount.value.type = 'text';
            bookCount.value.category = 'layout';
            bookCount.value.shortcode = 'random-book-count';
            bookCount.value.content = bookCount.value.content.toString();
            axios.post('/api/site-settings', bookCount.value)
                .then(response => {
                    if(response.data.success) {
                        toast.success('Book selection updated successfully');
                        bookCount.value = response.data.data;
                    } else {
                        toast.error('Error updating book selection');
                    }
                })
                .catch(error => {
                    toast.error('Error:', error);
                });
        };

        onMounted(() => {
            getSiteSettings();
        });

        return {
            heroText,
            heroImage,
            bookCount,
            onSubmitHeroImage,
            onSubmitHeroText,
            onSubmitBookCount,
            getSiteSettings,
        };
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>