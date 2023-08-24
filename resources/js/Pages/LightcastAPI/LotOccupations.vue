<template>
  <div>
    <Head title="LOT Occupations" />
    <h1 class="mb-4 text-3xl font-bold">LOT Occupations</h1>
    <p class="mb-4 leading-loose">The following displays LOT Occupations and LOT Specialized Occupations. These are referenced throughout the app, but you can reference the values within the database here.</p>

    <p class="leading-loose mb-4">If you would like to update the values stored in the database, you can do so by importing data from Lightcast. To do so go to Settings > Import/ Update LOT Occupations.</p>

    <Link class="btn-indigo inline-block" href="/settings/lot-occupations">
      <span>Import/ Update LOT Occupations</span>
    </Link>

    <div class="bg-white rounded-md shadow overflow-x-auto p-4 mt-8">
      <Notice>
        Version {{ lot_occupation_current_version }}
      </Notice>
      <DataTable
        :data="lot_occupations"
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
            <th>LOT ID</th>
            <th>Name</th>
            <th>Dimension</th>
            <th>Level</th>
            <th>Level Name</th>
            <th>Parent ID</th>
            <th>Description</th>
          </tr>
        </thead>
      </DataTable>
    </div>

  </div>
</template>

<script>
import { Head, Link} from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
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
    Link,
    DataTable,
    Notice
  },
  layout: Layout,
  props: {
    lot_occupations: {
      type: Array
    },
    lot_occupation_current_version: {
      type: String
    }
  }
}
</script>
