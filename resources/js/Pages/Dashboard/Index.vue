<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">Hey there! Welcome to the UCSD Division of Extended Studies Lightcast Data Dashboard.</p>

    <div class="bg-white rounded-md shadow p-8 mb-8">
      <h2 class="text-2xl mb-4">Data Explorers</h2>
      <ColumnThree>
        <CardLink href="#">
          <template #heading>
            Skills Explorer
          </template>
          <template #description>
            Under Construction
          </template>
        </CardLink>
        <CardLink href="#">
          <template #heading>
            Regional Explorer
          </template>
          <template #description>
            Under Construction
          </template>
        </CardLink>
        <CardLink href="#">
          <template #heading>
            Job Postings Explorer
          </template>
          <template #description>
            Under Construction
          </template>
        </CardLink>
        <CardLink href="#">
          <template #heading>
            Relatable Skills Explorer
          </template>
          <template #description>
            Under Construction
          </template>
        </CardLink>
        <CardLink href="#">
          <template #heading>
            Occupation Explorer
          </template>
          <template #description>
            Under Construction
          </template>
        </CardLink>
      </ColumnThree>
    </div>

    <div class="bg-white rounded-md shadow p-8 mb-8">
      <h2 class="text-2xl mb-4">Data APIs</h2>
      <p class="mb-4">View and export raw data directly from Lightcast or the local database.</p>
      <ColumnThree>
        <CardLink href="/lightcast-api/projected-occupation-growth">
          <template #heading>
            Projected Occupation Growth
          </template>
          <template #description>
            View projections for LOT Occupations and LOT Specialized Occupations.
          </template>
        </CardLink>
        <CardLink href="#">
          <template #heading>
            Aggregate Profiles
          </template>
          <template #description>
            Under Construction
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
        <CardLink href="/lightcast-api/lot-occupations">
          <template #heading>
            LOT Occupations
          </template>
          <template #description>
            Part of Lightcast's Classification API. View LOT Occupations and Specialized Occupations IDs. This data is stored within the local database.
          </template>
        </CardLink>
      </ColumnThree>
    </div>
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
