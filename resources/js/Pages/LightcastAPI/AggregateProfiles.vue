<template>
  <div>
    <Head title="Aggregate Profiles" />
    <h1 class="mb-2 text-3xl font-bold">Aggregate Profiles</h1>
    <p class="mb-4 text-sm leading-normal">Profiles are collected from various sources and processed/enriched to provide information such as standardized company name, occupation, skills, and geography.</p>
  </div>

  <div class="bg-white rounded-md shadow p-8 mb-8">
    <!--<form @submit.prevent="update">
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

    </form>-->
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
  },
  remember: 'form',
  /*data() {
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
  },*/
  methods: {
    update() {
      this.form.post('/lightcast-api/aggregate-profiles')
    }
  }
}
</script>

