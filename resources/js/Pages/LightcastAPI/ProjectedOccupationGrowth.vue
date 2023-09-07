<template>
  <div>
    <Head title="Projected Occupation Growth" />
    <h1 class="mb-2 text-3xl font-bold">Projected Occupation Growth</h1>
    <p class="mb-4 text-sm leading-normal">Lightcast Projected Occupation Growth provides an estimate of how a Lightcast Occupation is projected to grow over the next several years. This growth model incorporates information from Lightcast job postings and other historical and projection models base on BLS and OES data.</p>
    <p class="mb-8 text-sm leading-normal">National (United States) data will always be returned.</p>
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

        <div>
          <label class="form-label">Regions</label>
          <VueMultiselect
            v-model="form.region_ids"
            :multiple="true"
            :options="states"
            label="state"
            track-by="fips_code"
            placeholder="Select Regions"
            :close-on-select="false"
            :clear-on-select="false"
          >
          </VueMultiselect>
        </div>

      </ColumnTwo>

      <div class="mt-4">
        <loading-button class="btn-indigo" type="submit">Go</loading-button>
      </div>

    </form>
  </div>

  <div v-if="proj_occupation_growth">
    <div class="pb-4">
      <Notice>
        The following data is returned directly from Lightcast with no manipulation.
      </Notice>
    </div>

    <div v-for="occupation in proj_occupation_growth" class="bg-white rounded-md shadow mb-4">
      <div class="p-4">
        <h2 class="font-bold text-xl mb-2">
         {{ occupation.name }}
        </h2>
        <Notice>
         LOT ID: {{ occupation.lot_id }} / Dimension: {{ occupation.dimension }}
        </Notice>
        <p class="leading-normal mb-4">
          {{ occupation.description_us }}
        </p>

        <div class="py-4">
          <Line
            id="my-chart-id"
            :options="chartOptions"
            :data="occupation.chart_data"
          />
        </div>

        <div class="overflow-x-auto">
          <table class="w-full whitespace-nowrap">
            <thead>
              <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6">Region</th>
                <th class="pb-4 pt-6 px-6">Base Employment</th>
                <th class="pb-4 pt-6 px-6">Growth Year 1</th>
                <th class="pb-4 pt-6 px-6">Growth Year 2</th>
                <th class="pb-4 pt-6 px-6">Growth Year 3</th>
                <th class="pb-4 pt-6 px-6">Growth Year 5</th>
                <th class="pb-4 pt-6 px-6">Growth Year 10</th>
                <th class="pb-4 pt-6 px-6">LOT ID</th>
                <th class="pb-4 pt-6 px-6">Occupation</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="region in occupation.regions" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.region.name }}
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.baseEmployment }}
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.growthPercent.oneYear * 100 }}%
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.growthPercent.twoYear * 100 }}%
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.growthPercent.threeYear * 100 }}%
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.growthPercent.fiveYear * 100 }}%
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.growthPercent.tenYear * 100 }}%
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.id }}
                </td>
                <td class="border-t pb-4 pt-6 px-6">
                  {{ region.name }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
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
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'
import VueMultiselect from 'vue-multiselect'
import ColumnTwo from '@/Shared/ColumnTwo'
import Notice from '@/Shared/Notice'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Colors
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Colors
)

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
    Notice,
    Line
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
        region_ids: '',
      }),
      chartOptions: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Regions'
          }
        },
      }
    }
  },
  methods: {
    update() {
      this.form.post('/lightcast-api/projected-occupation-growth')
    }
  }
}
</script>
