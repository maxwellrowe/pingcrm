<template>
  <div>
    <Head title="Settings" />
    <h1 class="mb-8 text-3xl font-bold">Settings</h1>

    <h2 class="text-xl mb-4 font-bold">Update Database Tables</h2>
    <p class="mb-8">Use the following options to pull new data to the local database from Lightcast.</p>


    <ColumnThree>
      <CardLink href="/settings/lot-occupations">
        <template #heading>
          Lightcast Occupation Taxonomy - Import/ Update
        </template>
        <template #description>
          View latest data from Lightcast prior to importing. These values are used and referenced throughout the app.
        </template>
      </CardLink>
      <CardLink href="/settings/skills">
        <template #heading>
          Skills - Import/ Update
        </template>
        <template #description>
          View latest data from Lightcast prior to importing. These values are used and referenced throughout the app.
        </template>
      </CardLink>
      <CardLink href="/settings/skill-categories">
        <template #heading>
          Skill Categories and Subcategories - Import/ Update
        </template>
        <template #description>
          Import and update Skill Categories and subcategories. These values are used and referenced throughout the app.
        </template>
      </CardLink>
    </ColumnThree>

  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import CardLink from '@/Shared/CardLink'
import ColumnThree from '@/Shared/ColumnThree'

export default {
  components: {
    Head,
    Link,
    CardLink,
    ColumnThree
  },
  layout: Layout,
}
</script>
