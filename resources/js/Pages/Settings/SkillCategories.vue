<template>
  <div>
    <Head title="Import Skill Categories and Subcategories" />
    <h1 class="mb-4 text-3xl font-bold">Import Skill Categories and Subcategories</h1>
  </div>

  <div class="bg-white rounded-md shadow p-4 mb-8">
    <p class="mb-4 leading-relaxed">This importer will import Skill Categories from the Lightcast Open Skills API and Subcategories from the Classifications API. Click <span class="italic">Start Import</span> to update the database.</p>
    <p class="mb-4 leading-relaxed">NOTE: If you import/ update categories, you may also want to update Skills from within Settings.</p>

    <div class="mb-4">
      <button class="btn-indigo inline-block" tabindex="-1" type="button" @click="put">Start Import</button>
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
    <p class="mb-6 mt-2">If you would like to update to this version, click the <span class="italic">Start Import</span> button above.</p>

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

  export default {
    components: {
      Head,
      Icon,
      Link,
      LoadingButton,
      Notice
    },
    layout: Layout,
    props: {
      skills_version_info: Array,
      skills_current_version: String,
      apitoken: String
    },
    methods: {
      put() {
        if (confirm('Are you sure you want to overwrite the current database with new data?')) {
          this.$inertia.put(`/settings/skill-categories`)
        }
      }
    }
  }
</script>
