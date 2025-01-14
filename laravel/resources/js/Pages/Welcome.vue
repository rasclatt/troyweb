<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import LogoBlock from '@/Components/LogoBlock.vue';
import RandomBookSuggestions from '@/Components/RandomBookSuggestions.vue';
import AllBooksSearch from '@/Components/AllBooksSearch.vue';
import FooterComponent from '@/Layouts/Components/Footer.vue';

interface IWelcomePage {
    canLogin: boolean;
    canRegister: boolean;
    mainHeader: string;
    laravelVersion: string;
    phpVersion: string;
    heroImage: string;
}
const _ = (k: string, className: string): any => document.getElementById(k)?.classList.add(className);
const props = defineProps<IWelcomePage>();
const handleImageError = () => {
    _('screenshot-container', '!hidden');
    _('docs-card', '!row-span-1');
    _('docs-card-content', '!flex-row');
    _('background', '!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="relative min-h-[300px] lg:min-h-auto overflow-hidden bg-fixed bg-center bg-cover transition ease-in-out duration-500" :style="{ backgroundImage: `url('${props.heroImage}')` }">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <nav v-if="canLogin" class="-mx-3 flex flex-col md:flex-row justify-between fixed z-[1000] p-4 w-[100vw] bg-orange-500/80 backdrop-blur-sm shadow-md">
            <LogoBlock />
            <div class="flex flex-1 justify-end mt-4 md:mt-0">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >Account</Link>
        
            <template v-else>
                <Link
                :href="route('login')"
                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >Log in</Link>
        
                <Link
                v-if="canRegister"
                :href="route('register')"
                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >Register</Link>
            </template>
            </div>
        </nav>
        
        <div class="col-count-3 offset">
            <div class="start2 pt-[150px]" style="z-index: 1;">
                <h1 v-if="mainHeader" class="mt-4 text-center text-4xl font-bold text-white uppercase transition ease-in-out">{{ props.mainHeader }}</h1>
                <RandomBookSuggestions
                    centerHeader
                    class="text-2xl text-white font-semibold mb-[2em]"
                />
            </div>
        </div>
    
    </div>

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 col-count-3 offset py-8">
        <div class="start2 p-8">
            <AllBooksSearch />
        
        </div>
    </div>
    <FooterComponent />
</template>
