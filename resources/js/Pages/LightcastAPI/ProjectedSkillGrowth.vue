<template>
  <div>
    <Head title="Projected Skill Growth" />
    <h1 class="mb-2 text-3xl font-bold">Projected Skill Growth</h1>
    <p class="mb-4 text-sm">To measure the evolution of skill requirements in the labor market, Lightcast has developed a robust and dynamic skills taxonomy based on its analysis of hundreds of millions of online job postings, resumes, and social profiles. Skill projections extend the above insights to the future, and are to our knowledge the most advanced, granular, and up to date set of skill projections that exist in the industry.</p>
    <p class="mb-8 leading-leading-loose">NOTE: The Lightcast Projected Skill Growth API only allows for a single ID lookup. There are no regional options for Projected Skills Growth.</p>
  </div>

  <div class="bg-white rounded-md shadow p-8 mb-8">
    <form @submit.prevent="update">
      <label class="form-label">Search Skills</label>
      <VueMultiselect
        id="skill_id"
        v-model="selectedSkills"
        :multiple="false"
        :options="skills"
        label="name"
        track-by="id"
        placeholder="Search Skills"
        :close-on-select="true"
        :clear-on-select="true"
        @search-change="onSearchSkillsChange"
        @select="onSelectedSkill"
      >
      </VueMultiselect>

      <div class="mt-4">
        <loading-button class="btn-indigo" type="submit">Go</loading-button>
      </div>
    </form>
  </div>

  <div v-if="projected_skill_growth_datatables" class="bg-white rounded-md shadow overflow-x-auto p-8 mb-8">
    <h2 class="text-2xl mb-4">Projected Growth for {{ projected_skill_growth.name }}</h2>
    <Notice>
      The following data is returned directly from Lightcast with no manipulation.
    </Notice>
    <DataTable
      :data="projected_skill_growth_datatables"
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
          <th>Dimension</th>
          <th>Growth Category</th>
          <th>One Year %</th>
          <th>Two Year %</th>
          <th>Three Year %</th>
          <th>Five Year %</th>

        </tr>
      </thead>
    </DataTable>
  </div>

  <div v-if="related_skills_projected_growth" class="bg-white rounded-md shadow overflow-x-auto p-8">
    <h2 class="text-2xl mb-4">Projections for Skills Related to {{ projected_skill_growth.name }}</h2>
    <Notice>
      The following data is returned directly from Lightcast with no manipulation.
    </Notice>
    <DataTable
      :data="related_skills_projected_growth"
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
          <th>Dimension</th>
          <th>Growth Category</th>
          <th>One Year %</th>
          <th>Two Year %</th>
          <th>Three Year %</th>
          <th>Five Year %</th>

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
    Notice
  },
  layout: Layout,
  props: {
    skills: {
      type: Array,
      default: () => []
    },
    projected_skill_growth: Array,
    projected_skill_growth_datatables: Array,
    related_skills_projected_growth: Array,
    projected_skill_growth_id: String,
    related_skills_ids: Array
  },
  remember: 'form',
  data() {
    return {
      selectedSkills: undefined,
      form: this.$inertia.form({
        skill_id:''
      }),
    }
  },
  methods: {
    onSearchSkillsChange(term) {
      this.$inertia.get('/lightcast-api/projected-skill-growth', {term: term}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
      })
    },
    onSelectedSkill(skill) {
      this.form.skill_id = skill.id;
    },
    update() {
      this.form.post('/lightcast-api/projected-skill-growth')
    }
  }
}
</script>
