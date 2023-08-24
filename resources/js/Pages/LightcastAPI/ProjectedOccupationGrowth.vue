<template>
  <div>
    <Head title="Projected Occupation Growth" />
    <h1 class="mb-2 text-3xl font-bold">Projected Occupation Growth</h1>
    <p class="mb-8 text-sm">Lightcast Projected Occupation Growth provides an estimate of how a Lightcast Occupation is projected to grow over the next several years. This growth model incorporates information from Lightcast job postings and other historical and projection models base on BLS and OES data.</p>
  </div>

  <div class="bg-white rounded-md shadow p-8 mb-8">
    <form @submit.prevent="update">
      <ColumnTwo>

        <div>
          <label class="form-label">Occupations</label>
          <VueMultiselect
            v-model="form.occupations"
            :multiple="true"
            :options="lot_occupations"
            label="name"
            track-by="lot_id"
            placeholder="Select Occupations"
            :close-on-select="false"
            :clear-on-select="false"
          >
          </VueMultiselect>
        </div>

        <select-input v-model="form.region_id" :error="form.errors.region_id" class="pr-6 w-full lg:w-auto" label="Region (US or Choose State)">
          <!-- Add all of US as option -->
          <option value="us">
            United States
          </option>
          <option v-for="state in states" :value="state.fips_code">
            {{ state.state }}
          </option>
        </select-input>

      </ColumnTwo>

      <div class="mt-4">
        <loading-button class="btn-indigo" type="submit">Go</loading-button>
      </div>

    </form>
  </div>

  <div v-if="proj_occupation_growth" class="bg-white rounded-md shadow overflow-x-auto">
    <div class="p-4">
      <Notice>
        The following data is returned directly from Lightcast with no manipulation.
      </Notice>
    </div>
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Base Employment</th>
          <th class="pb-4 pt-6 px-6">Growth Year 1</th>
          <th class="pb-4 pt-6 px-6">Growth Year 2</th>
          <th class="pb-4 pt-6 px-6">Growth Year 3</th>
          <th class="pb-4 pt-6 px-6">Growth Year 5</th>
          <th class="pb-4 pt-6 px-6">Growth Year 10</th>
          <th class="pb-4 pt-6 px-6">Region</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="occupation in proj_occupation_growth" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.id }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.name }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.baseEmployment }}
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.growthPercent.oneYear * 100 }}%
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.growthPercent.twoYear * 100 }}%
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.growthPercent.threeYear * 100 }}%
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.growthPercent.fiveYear * 100 }}%
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.growthPercent.tenYear * 100 }}%
          </td>
          <td class="border-t pb-4 pt-6 px-6">
            {{ occupation.region.name }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>


</template>

<script>
import { Head, Link} from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'
import VueMultiselect from 'vue-multiselect'
import ColumnTwo from '@/Shared/ColumnTwo'
import Notice from '@/Shared/Notice'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    LoadingButton,
    SelectInput,
    TextInput,
    VueMultiselect,
    ColumnTwo,
    Notice
  },
  layout: Layout,
  props: {
    proj_occupation_growth: {
      type: Array,
      required: true
    },
    proj_occupation_meta: {
      type: Object
    },
    proj_occupation_dimensions: {
      type: Array
    },
    proj_occupation_region_levels: {
      type: Array
    },
    states: {
      type: Array
    },
    lot_occupations: {
      type: Array,
    },
    apitoken: String
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        occupations: '',
        region_id: '',
      }),
    }
  },
  methods: {
    update() {
      this.form.post('/lightcast-api/projected-occupation-growth')
    }
  }
}
</script>
