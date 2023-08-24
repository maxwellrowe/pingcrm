<template>
  <div>
    <Head title="Lightcast API" />
    <h1 class="mb-8 text-3xl font-bold">Lightcast Data APIs</h1>

    <p class="mb-8 leading-normal">Data APIs are views to utilize various Lightcast APIs.</p>

    <ColumnThree>
      <CardLink href="/lightcast-api/projected-occupation-growth">
        <template #heading>
          Projected Occupation Growth
        </template>
        <template #description>
          View projections for LOT Occupations and LOT Specialized Occupations.
        </template>
      </CardLink>
      <CardLink href="/lightcast-api/lot-occupations">
        <template #heading>
          LOT Occupations
        </template>
        <template #description>
          View LOT Occupations and Specialized Occupations IDs. This data is stored within the local database.
        </template>
      </CardLink>
      <CardLink href="/lightcast-api/projected-skill-growth">
        <template #heading>
          Projected Skill Growth
        </template>
        <template #description>
          This is an interface to get the projected growth of demand for a skill over the next one, two, three, and five years.
        </template>
      </CardLink>
      <CardLink href="/lightcast-api/skills">
        <template #heading>
          Skills
        </template>
        <template #description>
          Lightcast's complete collection of skills. This API exposes all of our skills along with their type and unique identifier.
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
