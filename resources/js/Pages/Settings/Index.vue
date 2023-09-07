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

  <div class="border-t border-solid mt-4">
    <h2 class="text-xl pt-8 mb-4 font-bold">Authorization Keys for APIs</h2>
    <p class="mb-8 leading-normal">Authorization keys last for one hour, then need to be renewed. The following is just for reference. Each API has different authorization keys.</p>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">API</th>
            <th class="pb-4 pt-6 px-6">Updated</th>
            <th class="pb-4 pt-6 px-6">Authorization Key</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Classifications API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ classifications_access_key_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ classifications_access_key }}
            </td>
          </tr>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Job Postings API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ job_postings_access_key_value_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ job_postings_access_key_value }}
            </td>
          </tr>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Projected Occupation Growth API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ projected_occupation_growth_access_key_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ projected_occupation_growth_access_key }}
            </td>
          </tr>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Projected Skills Growth API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ skills_growth_access_key_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ skill_growth_access_key }}
            </td>
          </tr>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Aggregate Profiles API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ open_api_access_key_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ open_api_access_key }}
            </td>
          </tr>
          <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t pb-4 pt-6 px-6">
              Open API
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ aggregate_profiles_key_updated }}
            </td>
            <td class="border-t pb-4 pt-6 px-6">
              {{ aggregate_profiles_key }}
            </td>
          </tr>
        </tbody>
      </table>
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
    ColumnThree,
  },
  layout: Layout,
  props: {
    'skill_growth_access_key': String,
    'skills_growth_access_key_updated': Date,
    'open_api_access_key': String,
    'open_api_access_key_updated': Date,
    'classifications_access_key': String,
    'classifications_access_key_updated': Date,
    'projected_occupation_growth_access_key': String,
    'projected_occupation_growth_access_key_updated': Date,
    'job_postings_access_key_value': String,
    'job_postings_access_key_value_updated': Date,
    'aggregate_profiles_key': String,
    'aggregate_profiles_key_updated': Date
  }
}
</script>
