<template>
  <div>
    <Head title="Skills" />
    <h1 class="mb-4 text-3xl font-bold">Skills</h1>
    <Notice>
      Version {{ version }} <a href="https://docs.lightcast.dev/updates/skills-taxonomy-changelog" target="_blank" class="link">Changelog</a>
    </Notice>
    <p class="mb-4 leading-loose">The following displays Skills based on the Skills API from Lightcast. These are referenced throughout the app as well. This data is stored locally in the database.</p>

    <p class="leading-loose mb-4">If you would like to update the values stored in the database, you can do so by importing data from Lightcast. To do so go to Settings > Import/ Update Skills.</p>

    <div class="bg-white rounded-md shadow overflow-x-auto p-4 mt-8">

      <div class="bg-white rounded-md shadow p-8 mb-8">
        <form @submit.prevent="update">

            <div>
              <label class="form-label">Select a Skill Type Below</label>
              <VueMultiselect
                v-model="form.skill_type"
                :multiple="false"
                :options="skill_types"
                label="name"
                track-by="id"
                placeholder="Select a Type"
                :close-on-select="true"
                :clear-on-select="false"
              >
              </VueMultiselect>
            </div>

          <div class="mt-4">
            <loading-button class="btn-indigo" type="submit">Go</loading-button>
          </div>

        </form>
      </div>

      <div v-if="skills">
        <DataTable
          :data="skills"
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
              <th>Category</th>
              <th>Subcategory</th>
              <th>Type ID</th>
              <th>Type Name</th>
            </tr>
          </thead>
        </DataTable>
      </div>
    </div>

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
import VueMultiselect from 'vue-multiselect'
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
    VueMultiselect,
    DataTable,
    Notice,
  },
  layout: Layout,
  props: {
    skills: Array,
    skill_types: Array,
    version: String
  },
  data() {
    return {
      form: this.$inertia.form({
        skill_type: ''
      })
    }
  },
  methods: {
    update() {
      this.form.post('/lightcast-api/skills')
    }
  }
}
</script>

