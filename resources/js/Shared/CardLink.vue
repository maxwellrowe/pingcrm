<template>
  <Link
    class="btn-indigo shadow-lg block whitespace-normal p-6 leading-snug"
  >
    <h2 class="text-xl font-bold mb-2 text-white" v-if="$slots.heading">
      <slot name="heading" />
    </h2>
    <p class="text-white font-normal" v-if="$slots.description">
      <slot name="description" />
    </p>
  </Link>
</template>

<script>
import { Link} from '@inertiajs/inertia-vue3'

export default {
  components: {
    Link
  }
}
</script>
