<template>
  <div id="unAnswered" class="app">
    <notifications position="bottom left" :duration="5000" />

    <!-- {{-- refresh btn --}} -->
    <div class="refresh-div">
      <div @click="getData()" class="refresh"></div>
    </div>

    <!-- content -->
    <div class="container-fluid" v-if="unAnswered.details">
      <h3 class="my-2">{{ $t('UN_ANS.title') }}</h3>

      <!-- detail -->
      <div class="table-shadow detail row">
        <!-- report detail -->
        <div class="col-12 col-md-6">
          <h5>
            {{ $t('GENERAL.report.title') }}
          </h5>
          <ul>
            <li>
              <span>{{ $t('GENERAL.report.queue') }}</span
              ><span>{{ home.queues ? showLable(home.queues) : $t('GENERAL.empty') }}</span>
            </li>
            <li>
              <span>{{ $t('GENERAL.report.fromFilter') }}</span
              ><span>{{ home.fromFilterFaLable ? home.fromFilterFaLable : $t('GENERAL.empty') }}</span>
            </li>
            <li>
              <span> {{ $t('GENERAL.report.toFilter') }}</span
              ><span>{{ home.toFilterFaLable ? home.toFilterFaLable : $t('GENERAL.empty') }}</span>
            </li>
            <li>
              <span> {{ $t('GENERAL.report.range') }}</span
              ><span>{{ home.timeFilter ? $t(`HOME.${home.timeFilter.code}`) : $t('GENERAL.empty') }}</span>
            </li>
          </ul>
        </div>
        <!-- call detail -->
        <div class="col-12 col-md-6" v-if="unAnswered.details">
          <h5>{{ $t('UN_ANS.detail.title') }}</h5>
          <ul>
            <li>
              <span> {{ $t('UN_ANS.detail.unAnswered') }} </span><span>{{ unAnswered.details.detail.count ? unAnswered.details.detail.count : 0 }} {{ $t('GENERAL.call') }}</span>
            </li>
            <li>
              <span> {{ $t('UN_ANS.detail.delay') }} </span><span v-html="unAnswered.details.detail.time ? secondsToDay(unAnswered.details.detail.time / 1000 / unAnswered.details.detail.count) : 0"></span>
            </li>
            <li>
              <span> {{ $t('UN_ANS.detail.num') }} </span><span>{{ unAnswered.details.numInQueue ? unAnswered.details.numInQueue.numInQueue : '-' }} {{ $t('GENERAL.person') }}</span>
            </li>
            <li>
              <span> {{ $t('UN_ANS.detail.avgDelay') }}</span
              ><span v-html="unAnswered.details.detail.delay ? secondsToDay(unAnswered.details.detail.delay / unAnswered.details.detail.count) : 0"></span>
            </li>
          </ul>
        </div>
      </div>

      <!-- دلیل قطع شدن مکالمه  -->
      <div class="table-shadow row" v-if="unAnswered.hangUp.length">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('UN_ANS.disconnection.GUIDE')">
              <p>{{ $t('UN_ANS.disconnection.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('UN_ANS.disconnection.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('UnANSWERED_hangUp', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('UnANSWERED_hangUp', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('UnANSWERED_hangUp', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsHangUp" :rows="unAnswered.hangUp" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'pCount'">
                <span dir="rtl">{{ props.row.count != '0' ? ((props.row.count * 100) / unAnswered.details.detail.count).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export -->
        <table class="mt-3" id="UnANSWERED_hangUp" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('UN_ANS.disconnection.event') }}</th>
              <th>{{ $t('UN_ANS.disconnection.received') }}</th>
              <th>{{ $t('UN_ANS.disconnection.pCount') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in unAnswered.hangUp" :key="indexTd">
              <td>{{ td.lable }}</td>
              <td>{{ td.count }} {{ $t('GENERAL.call') }}</td>
              <td>{{ td.count != '0' ? ((td.count * 100) / unAnswered.details.detail.count).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- تماس های بدون پاسخ  در صف -->
      <div class="table-shadow row" v-if="unAnswered.queueUnAnswered">
        <!-- table -->
        <div class="col-12 col-md-6">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('UN_ANS.queue.GUIDE')">
              <p>{{ $t('UN_ANS.queue.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('UN_ANS.queue.title') }}</h5>
          </div>

          <!--vue good table -->
          <div class="w-100" dir="ltr">
            <vue-good-table :columns="columnsQueueUnAnswered" :rows="unAnswered.queueUnAnswered" :search-options="optionsTable">
              <!-- customize fields  -->
              <template #table-row="props">
                <span v-if="props.column.field == 'pCount'">
                  <span dir="rtl">{{ ((props.row.count * 100) / unAnswered.details.detail.count).toFixed(2) }} {{ $t('GENERAL.percentage') }}</span>
                </span>
                <span v-else>
                  {{ props.formattedRow[props.column.field] }}
                </span>
              </template>
            </vue-good-table>
          </div>
        </div>
        <!-- chart -->
        <div class="col-12 col-md-6">
          <barChart :data="unAnswered.queueUnAnswered"></barChart>
        </div>
      </div>
    </div>
    <!-- loader -->
    <div class="loader-ctn d-flex align-items-center justify-content-center" style="height: 75vh" v-if="!unAnswered.details">
      <div class="loader-wait-request"></div>
    </div>
  </div>
</template>

<script>

/** import pinia homeStore*/
import { useHome } from '../js/pinia/home'
import { useUnAnswered } from '../js/pinia/unAnswered'
import { useGeneral } from '../js/pinia/general'


// helper
import helper from '../js/helper'

var moment = require('moment-jalaali')

// import chart
import barChart from './chart/BarChart.vue'

// import vue good table
import { VueGoodTable } from 'vue-good-table-next';


export default {
  name: 'unAnswered',
  mixins: [helper],
  data() {
    return {
      isLoading: false,

      optionsTable: {
        enabled: true,
        placeholder: this.$t('GENERAL.searchFeild')
      },
      paginationOptions: {
        enabled: true,
        mode: 'records',
        perPage: 10,
        position: 'bottom',
        perPageDropdown: [10, 25, 50, 100],
        dropdownAllowAll: false,
        firstLabel: this.$t('GENERAL.firstPage'),
        lastLabel: this.$t('GENERAL.LastPage'),
        nextLabel: this.$t('GENERAL.next'),
        prevLabel: this.$t('GENERAL.back'),
        rowsPerPageLabel: this.$t('GENERAL.rowsperpage'),
        ofLabel: this.$t('GENERAL.of'),
        pageLabel: this.$t('GENERAL.page'), // for 'pages' mode
        allLabel: this.$t('GENERAL.all'),
      },

      columnsHangUp: [
        {
          label: this.$t('UN_ANS.disconnection.pCount'),
          field: 'pCount',
          sortable: false,
        },
        {
          label: this.$t('UN_ANS.disconnection.received'),
          field: 'count',
          type: 'number',

        },
        {
          label: this.$t('UN_ANS.disconnection.event'),
          field: 'lable',
          sortable: false,
        },
      ],

      columnsQueueUnAnswered: [
        {
          label: this.$t('UN_ANS.queue.pCount'),
          field: 'pCount',
          sortable: false,
        },
        {
          label: this.$t('UN_ANS.queue.received'),
          field: 'count',
          type: 'number',

        },
        {
          label: this.$t('UN_ANS.queue.queue'),
          field: 'lable',
        },
      ],



    }
  },
  methods: {
    /** show lable object */
    showLable(items) {
      let lable = ''
      items.map((item) => {
        lable += ', ' + item.title
      })
      return lable.substring(1)
    },

    /** get data from api */
    async getData() {
      /** back to home component */
      if (!this.home.queues.length) return this.general.route = '/home'

      try {
        this.isLoading = true;

        let agents = []
        this.home.agents.map((item) => { return agents.push(item.code) })
        let queues = []
        this.home.queues.map((item) => { return queues.push(item.code) })

        let data = {
          'method': 'unAnswered_getData',
          'queues': queues,
          'agents': agents,
          'timeFilter': this.home.timeFilter,
          'toFilter': this.home.toFilter ? moment(this.home.toFilter + ' ' + this.home.toTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
          'fromFilter': this.home.fromFilter ? moment(this.home.fromFilter + ' ' + this.home.fromTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
        }
        let req = await this.$axios({
          url: '/unAnsweredActions',
          data: data
        })
        /**start save req for show in page unAnswered */
        this.unAnswered.hangUp = req.data.hangUp
        this.unAnswered.details = req.data.details
        this.unAnswered.queueUnAnswered = req.data.qUnAnswered
        /**end save req for show in page unAnswered */
      } catch (error) {
        console.error(error);
      }
      this.isLoading = false;
    },

  },
  components: {
    barChart,
    VueGoodTable
  },
  mounted() {
    this.getData()
  },
  setup() {
    const home = useHome()
    const unAnswered = useUnAnswered()
    const general = useGeneral()

    return {
      home,
      general,
      unAnswered
    }
  }
}
</script>

<style lang='scss'>
#unAnswered {
}
</style>