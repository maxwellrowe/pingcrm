<template>
  <div>
    <Head title="Import LOT Occupations" />
    <h1 class="mb-4 text-3xl font-bold">Import LOT Occupations</h1>
  </div>

  <div class="bg-white rounded-md shadow p-4 mb-8">
    <p class="mb-4 leading-relaxed">This importer will import both LOT Occupations and LOT Specialized Occupations. Click <span class="italic">Start Import</span> to update the database. Prior to clicking <span class="italic">Start Import</span> you can review the data below.</p>

    <div class="mb-4">
      <button class="btn-indigo inline-block" tabindex="-1" type="button" @click="put">Start Import LOT Occupations</button>
    </div>
    <Notice>
      Last Version Imported: {{ lot_occupation_current_version }}
    </Notice>
    &nbsp;
    <Notice>
      Latest Version: {{ lot_occupation_version_latest }}
    </Notice>
  </div>

  <div class="bg-white rounded-md shadow overflow-x-auto p-4">
    <h2 class="mb-1 text-xl font-bold">Review Import Data (Version {{ lot_occupation_version_latest }})</h2>
    <p class="mb-8">The below data will be imported upon clicking <span class="italic">Start Import LOT Occupations</span> button above.</p>

    <DataTable
      :data="lot_occupations_data_tables"
      :options="{
        dom: 'Blfrtip',
        buttons: [{
          extend: 'csv',
          text: 'Export to CSV',
          className: 'btn-indigo mb-4',
        }],
        lengthMenu: [
          [50, 100, 250, -1],
          [50, 100, 250, 'All']
        ],
        pageLength: 50,
        select: true
      }"
      :columns="columns"
      class="display"
      width="100%"
    >
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Level Name</th>
          <th>Level</th>
          <th>Parent ID</th>
          <th>Description</th>
        </tr>
      </thead>
    </DataTable>

    <!-- OLD: Keeping for example purposes
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Level Name</th>
          <th class="pb-4 pt-6 px-6">Level</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lot_occupation in lot_occupations" :key="lot_occupation.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t pb-4 pt-6 px-6">
            {{ lot_occupation.id }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ lot_occupation.name }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ lot_occupation.levelName }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ lot_occupation.level }}
          </td>
        </tr>
        <tr v-if="lot_occupations.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No resuls found. There was an error in pulling data. Please try refreshing.</td>
        </tr>
      </tbody>
    </table>
  -->

  </div>


</template>

<script>
  import { Head, Link} from '@inertiajs/inertia-vue3'
  import Icon from '@/Shared/Icon'
  import pickBy from 'lodash/pickBy'
  import Layout from '@/Shared/Layout'
  import throttle from 'lodash/throttle'
  import mapValues from 'lodash/mapValues'
  import LoadingButton from '@/Shared/LoadingButton'
  import Notice from '@/Shared/Notice'

  // Datatables
  import DataTable from 'datatables.net-vue3'
  import DataTablesLib from 'datatables.net'
  import 'datatables.net-buttons'
  import 'datatables.net-buttons/js/buttons.html5';
  import 'datatables.net-select'

  DataTable.use(DataTablesLib);

  export default {
    components: {
      Head,
      Icon,
      Link,
      LoadingButton,
      DataTable,
      Notice
    },
    layout: Layout,
    props: {
      lot_occupations: {
        type: Array,
        required: true
      },
      lot_occupations_data_tables: {
        type: Array
      },
      lot_occupation_versions: {
        type: Array
      },
      lot_occupation_version_latest: {
        type: String
      },
      lot_occupation_current_version: {
        type: String
      },
      apitoken: String
    },
    methods: {
      put() {
        if (confirm('Are you sure you want to overwrite the current database with new data?')) {
          this.$inertia.put(`/settings/lot-occupations`)
        }
      }
    }
  }
</script>
