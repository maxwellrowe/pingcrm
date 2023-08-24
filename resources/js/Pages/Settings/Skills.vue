<template>
  <div>
    <Head title="Import Skills" />
    <h1 class="mb-4 text-3xl font-bold">Import Skills</h1>
  </div>

  <div class="bg-white rounded-md shadow p-4 mb-8">
    <p class="mb-4 leading-relaxed">This importer will import Skills from the Lightcast Open Skills API. Click <span class="italic">Start Import</span> to update the database. Prior to clicking <span class="italic">Start Import</span> you can review the data below.</p>

    <div class="mb-4">
      <p class="mb-4 leading-relaxed">NOTE: If you import/ update categories, you may also want to update Skill Categories from within Settings.</p>
      <p class="font-bold underline mb-2">Under Construction -- Do Not Import!</p>
      <button class="btn-indigo inline-block" tabindex="-1" type="button" @click="put">Start Import Skills</button>
    </div>
    <Notice>
      Last Version Imported: {{ skills_current_version }}
    </Notice>
    &nbsp;
    <Notice>
      Latest Version: {{ skills_version_info.version }}
    </Notice>
  </div>

  <div class="bg-white rounded-md shadow overflow-x-auto p-4 mb-8">
    <h2 class="mb-1 text-xl font-bold mb-4">Info About Latest Version of Skills</h2>
    <p class="mb-6 mt-2">If you would like to update to this version, click the <span class="italic">Start Import Skills</span> button above.</p>

    <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
        <div class="flex flex-col pb-3">
            <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Removed Skill Count</dt>
            <dd class="text-lg font-semibold">{{ skills_version_info.removedSkillCount.toLocaleString('en-US') }}</dd>
        </div>
        <div class="flex flex-col py-3">
            <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Total Skill Count</dt>
            <dd class="text-lg font-semibold">{{ skills_version_info.skillCount.toLocaleString('en-US') }}</dd>
        </div>
        <div class="flex flex-col py-3">
            <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Version</dt>
            <dd class="text-lg font-semibold">{{ skills_version_info.version }}</dd>
        </div>
    </dl>
  </div>

  <div class="bg-white rounded-md shadow overflow-x-auto p-4">
    <h2 class="mb-1 text-xl font-bold mb-4">Example of Data Import</h2>

    <DataTable
      :data="skills_data_tables"
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
          <th>Description</th>
          <th>Type ID</th>
          <th>Type Name</th>
          <th>Category ID</th>
          <th>Category Name</th>
          <th>Subcategory ID</th>
          <th>Subcategory Name</th>
          <th>Is Software</th>
          <th>Is Language</th>
          <th>Info URL</th>
        </tr>
      </thead>
    </DataTable>

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
      skills_version_info: Array,
      skills_data_tables: Array,
      skills_current_version: String,
      apitoken: String
    },
    methods: {
      put() {
        if (confirm('Are you sure you want to overwrite the current database with new data?')) {
          this.$inertia.put(`/settings/skills`)
        }
      }
    }
  }
</script>
