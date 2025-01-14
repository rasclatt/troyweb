<template>
    <font-awesome-icon icon="star" class="text-orange-500" v-for="star in rating" :key="star" />
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';

export default defineComponent({
  name: 'StarRatings',
  props: {
    rating: {
      type: Number,
      required: true
    }
  },
});
</script>