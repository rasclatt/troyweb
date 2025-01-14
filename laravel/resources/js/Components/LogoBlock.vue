<template>
    <div :class="['flex', center ? 'justify-center' : 'justify-start md:justify-start', 'gap-2']">
        <img src="/images/library-logo.svg" alt="Logo" class="h-8 w-auto">
        <h5 :class="['text-lg font-semibold uppercase my-1', `${color}`]"><Link href="/">Troy Library</Link></h5>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
export default defineComponent({
    name: 'LogoBlock',
    components: {
        Link,
    },
    props: {
        center: {
            type: Boolean,
            default: false,
        },
        color: {
            type: String,
            default: 'text-white',
        },
    },
});
</script>