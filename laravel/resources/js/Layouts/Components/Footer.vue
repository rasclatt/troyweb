<template>
    <footer :class="{'mt-4': !skipMargin}" class="col-count-3 offset py-8 bg-orange-500 text-white h-[120px]">
        <div class="container start2">
            <p>&copy; {{ new Date().getFullYear() }} TroyWeb Library. All rights reserved.</p>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'FooterComponent',
    props: {
        skipMargin: {
            type: Boolean,
            default: false
        }
    }
};
</script>