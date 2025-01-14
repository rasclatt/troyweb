<template>
    <div class="relative min-h-[100vh] flex items-center justify-center overflow-hidden bg-fixed bg-center bg-cover transition ease-in-out duration-50" :style="{ backgroundImage: `url('${$page.props.heroImage}')` }">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative z-10 flex items-center justify-center min-h-[300px] lg:min-h-[100vh]">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
                <div class="mb-4">
                    <LogoBlock :center="true" color="text-orange-500" />
                </div>
                <slot />
            </div>
        </div>
    </div>
    <FooterComponent skipMargin />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import LogoBlock from '@/Components/LogoBlock.vue';
import FooterComponent from '@/Layouts/Components/Footer.vue';

export default defineComponent({
    components: {
        LogoBlock,
        FooterComponent
    },
});
</script>