<template>
  <div id="distribution" class="app">
    <notifications position="bottom left" :duration="5000" />

    <!-- {{-- refresh btn --}} -->
    <div class="refresh-div">
      <div @click="getData()" class="refresh"></div>
    </div>

    <!-- content -->
    <div class="container-fluid" v-if="distribution.details">
      <h3 class="my-2">{{ $t('DIS.title') }}</h3>

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
        <div class="col-12 col-md-6" v-if="distribution.details">
          <h5>{{ $t('DIS.detail.title') }}</h5>
          <ul>
            <li>
              <span> {{ $t('DIS.detail.answered') }} </span><span>{{ distribution.details.answered }} {{ $t('GENERAL.call') }}</span>
            </li>
            <li>
              <span> {{ $t('DIS.detail.unAnswered') }} </span><span> {{ distribution.details.Unanswered }} {{ $t('GENERAL.call') }}</span>
            </li>
            <li>
              <span> {{ $t('DIS.detail.login') }} </span><span> {{ distribution.details.login }} {{ $t('GENERAL.person') }}</span>
            </li>
            <li>
              <span> {{ $t('DIS.detail.logout') }} </span><span>{{ distribution.details.logout }} {{ $t('GENERAL.person') }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- میانگین زمان انتظار در ساعت-->
      <div class="table-shadow row" v-if="distribution.waitByDate">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.wait.GUIDE')">
              <p>{{ $t('DIS.wait.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.wait.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('DIS_waitByDate', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('DIS_waitByDate', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('DIS_waitByDate', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsWaitByDate" :rows="distribution.waitByDate" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'date'">
                <span dir="rtl">{{ jdate(props.row.date, 'jYYYY/jMM/jDD') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pAnswered'">
                <span dir="rtl">{{ props.row.countAnswered ? ((props.row.countAnswered * 100) / (props.row.countAnswered * 1 + (props.row.countUnanswered ? props.row.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pUnAnswered'">
                <span dir="rtl">{{ props.row.countUnanswered ? ((props.row.countUnanswered * 100) / (props.row.countUnanswered * 1 + (props.row.countAnswered ? props.row.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data2Answered'">
                <span dir="rtl">{{ props.row.data2Answered ? props.row.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data1Answered'">
                <span dir="rtl">{{ props.row.data1Answered ? props.row.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export -->
        <table class="mt-3" id="DIS_waitByDate" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('DIS.wait.data') }}</th>
              <th>{{ $t('DIS.wait.answered') }}</th>
              <th>{{ $t('DIS.wait.pAnswered') }}</th>
              <th>{{ $t('DIS.wait.unAnswered') }}</th>
              <th>{{ $t('DIS.wait.pUnAnswered') }}</th>
              <th>{{ $t('DIS.wait.avgTime') }}</th>
              <th>{{ $t('DIS.wait.avgWait') }}</th>
              <th>{{ $t('DIS.wait.login') }}</th>
              <th>{{ $t('DIS.wait.logout') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in distribution.waitByDate" :key="indexTd">
              <td>{{ td.date ? jdate(td.date, 'jYYYY/jMM/jDD') : 0 }}</td>
              <td>{{ td.countAnswered ? td.countAnswered : 0 }}</td>
              <td>{{ td.countAnswered ? ((td.countAnswered * 100) / (td.countAnswered * 1 + (td.countUnanswered ? td.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.countUnanswered ? td.countUnanswered : 0 }}</td>
              <td>{{ td.countUnanswered ? ((td.countUnanswered * 100) / (td.countUnanswered * 1 + (td.countAnswered ? td.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data2Answered ? td.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.data1Answered ? td.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.countLogin ? td.countLogin : 0 }}</td>
              <td>{{ td.countLogout ? td.countLogout : 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- پراکندگی تماس ها در ساعت -->
      <div class="table-shadow row" v-if="distribution.waitByTime">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.dispersion.GUIDE')">
              <p>{{ $t('DIS.dispersion.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.dispersion.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('DIS_waitByTime', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('DIS_waitByTime', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('DIS_waitByTime', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsWaitByTime" :rows="distribution.waitByTime" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'pAnswered'">
                <span dir="rtl">{{ props.row.countAnswered ? ((props.row.countAnswered * 100) / (props.row.countAnswered * 1 + (props.row.countUnanswered ? props.row.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pUnAnswered'">
                <span dir="rtl">{{ props.row.countUnanswered ? ((props.row.countUnanswered * 100) / (props.row.countUnanswered * 1 + (props.row.countAnswered ? props.row.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data2Answered'">
                <span dir="rtl">{{ props.row.data2Answered ? props.row.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data1Answered'">
                <span dir="rtl">{{ props.row.data1Answered ? props.row.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export  -->
        <table class="mt-3" id="DIS_waitByTime" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('DIS.dispersion.time') }}</th>
              <th>{{ $t('DIS.dispersion.answered') }}</th>
              <th>{{ $t('DIS.dispersion.pAnswered') }}</th>
              <th>{{ $t('DIS.dispersion.unAnswered') }}</th>
              <th>{{ $t('DIS.dispersion.pUnAnswered') }}</th>
              <th>{{ $t('DIS.dispersion.avgTime') }}</th>
              <th>{{ $t('DIS.dispersion.avgWait') }}</th>
              <th>{{ $t('DIS.dispersion.login') }}</th>
              <th>{{ $t('DIS.dispersion.logout') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in distribution.waitByTime" :key="indexTd">
              <td>{{ td.hour }}</td>
              <td>{{ td.countAnswered ? td.countAnswered : 0 }}</td>
              <td>{{ td.countAnswered ? ((td.countAnswered * 100) / (td.countAnswered * 1 + (td.countUnanswered ? td.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.countUnanswered ? td.countUnanswered : 0 }}</td>
              <td>{{ td.countUnanswered ? ((td.countUnanswered * 100) / (td.countUnanswered * 1 + (td.countAnswered ? td.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data2Answered ? td.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.data1Answered ? td.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.countLogin ? td.countLogin : 0 }}</td>
              <td>{{ td.countLogout ? td.countLogout : 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!--  نمودار پاسخ داده شده / پاسخ داده نشده در ساعت -->
      <div class="table-shadow row" v-if="distribution.chartAnswered">
        <div class="col-12">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.chartAnswered.GUIDE')">
              <p>{{ $t('DIS.chartAnswered.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.chartAnswered.title') }}</h5>
          </div>
          <!-- chart -->
          <barChart :data="distribution.chartAnswered"></barChart>
        </div>
      </div>

      <!--  نمودار میانگین  زمان صحبت در ساعت -->
      <div class="table-shadow row" v-if="distribution.chartTimeAnswered">
        <div class="col-12">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.chartTimeAnswered.GUIDE')">
              <p>{{ $t('DIS.chartTimeAnswered.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.chartTimeAnswered.title') }}</h5>
          </div>
          <!-- chart -->
          <barChart :data="distribution.chartTimeAnswered"></barChart>
        </div>
      </div>

      <!--  نمودار میانگین زمان انتظار در ساعت -->
      <div class="table-shadow row" v-if="distribution.chartDelayAnswered">
        <div class="col-12">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.chartDelayAnswered.GUIDE')">
              <p>{{ $t('DIS.chartDelayAnswered.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.chartDelayAnswered.title') }}</h5>
          </div>
          <!-- chart -->
          <barChart :data="distribution.chartDelayAnswered"></barChart>
        </div>
      </div>

      <!-- پراکندگی تماس ها در روز های هفته -->
      <div class="table-shadow row" v-if="distribution.answeredInWeek">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.dispersionInWeek.GUIDE')">
              <p>{{ $t('DIS.dispersionInWeek.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.dispersionInWeek.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('DIS_answeredInWeek', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('DIS_answeredInWeek', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('DIS_answeredInWeek', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsAnsweredInWeek" :rows="distribution.answeredInWeek" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'day'">
                <span dir="rtl">{{ showDay(props.row.day) }}</span>
              </span>
              <span v-else-if="props.column.field == 'pAnswered'">
                <span dir="rtl">{{ props.row.countAnswered ? ((props.row.countAnswered * 100) / (props.row.countAnswered * 1 + (props.row.countUnanswered ? props.row.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pUnAnswered'">
                <span dir="rtl">{{ props.row.countUnanswered ? ((props.row.countUnanswered * 100) / (props.row.countUnanswered * 1 + (props.row.countAnswered ? props.row.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data2Answered'">
                <span dir="rtl">{{ props.row.data2Answered ? props.row.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data1Answered'">
                <span dir="rtl">{{ props.row.data1Answered ? props.row.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export -->
        <table class="mt-3" id="DIS_answeredInWeek" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('DIS.dispersionInWeek.day') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.answered') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.pAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.unAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.pUnAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.avgTime') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.avgWait') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.login') }}</th>
              <th>{{ $t('DIS.dispersionInWeek.logout') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in distribution.answeredInWeek" :key="indexTd">
              <td>{{ showDay(td.day) }}</td>
              <td>{{ td.countAnswered ? td.countAnswered : 0 }}</td>
              <td>{{ td.countAnswered ? ((td.countAnswered * 100) / (td.countAnswered * 1 + (td.countUnanswered ? td.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.countUnanswered ? td.countUnanswered : 0 }}</td>
              <td>{{ td.countUnanswered ? ((td.countUnanswered * 100) / (td.countUnanswered * 1 + (td.countAnswered ? td.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data2Answered ? td.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.data1Answered ? td.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.countLogin ? td.countLogin : 0 }}</td>
              <td>{{ td.countLogout ? td.countLogout : 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!--  نمودار پاسخ به تماس ها براساس روزهای هفته -->
      <div class="table-shadow row" v-if="distribution.chartAnsweredWeek">
        <div class="col-12">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.chartAnsweredWeek.GUIDE')">
              <p>{{ $t('DIS.chartAnsweredWeek.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.chartAnsweredWeek.title') }}</h5>
          </div>
          <!-- chart -->
          <barChart :data="distribution.chartAnsweredWeek"></barChart>
        </div>
      </div>

      <!--  نمودار میانگین مدت تماس براساس روزهای هفته -->
      <div class="table-shadow row" v-if="distribution.chartTimeAnsweredWeek">
        <div class="col-12">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.chartTimeAnsweredWeek.GUIDE')">
              <p>{{ $t('DIS.chartTimeAnsweredWeek.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.chartTimeAnsweredWeek.title') }}</h5>
          </div>
          <!-- chart -->
          <barChart :data="distribution.chartTimeAnsweredWeek"></barChart>
        </div>
      </div>

      <!-- پراکندگی تماس ها در ماه -->
      <div class="table-shadow row" v-if="distribution.answeredInMonth">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('DIS.dispersionInMonth.GUIDE')">
              <p>{{ $t('DIS.dispersionInMonth.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('DIS.dispersionInMonth.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('DIS_answeredInMonth', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('DIS_answeredInMonth', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('DIS_answeredInMonth', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsAnsweredInMonth" :rows="distribution.answeredInMonth" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'month'">
                <span dir="rtl">{{ props.row.month }}</span>
              </span>
              <span v-else-if="props.column.field == 'pAnswered'">
                <span dir="rtl">{{ props.row.countAnswered ? ((props.row.countAnswered * 100) / (props.row.countAnswered * 1 + (props.row.countUnanswered ? props.row.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pUnAnswered'">
                <span dir="rtl">{{ props.row.countUnanswered ? ((props.row.countUnanswered * 100) / (props.row.countUnanswered * 1 + (props.row.countAnswered ? props.row.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data2Answered'">
                <span dir="rtl">{{ props.row.data2Answered ? props.row.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data1Answered'">
                <span dir="rtl">{{ props.row.data1Answered ? props.row.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</span>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export -->
        <table class="mt-3" id="DIS_answeredInMonth" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('DIS.dispersionInMonth.month') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.answered') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.pAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.unAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.pUnAnswered') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.avgTime') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.avgWait') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.login') }}</th>
              <th>{{ $t('DIS.dispersionInMonth.logout') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in distribution.answeredInMonth" :key="indexTd">
              <td>{{ td.month }}</td>
              <td>{{ td.countAnswered ? td.countAnswered : 0 }}</td>
              <td>{{ td.countAnswered ? ((td.countAnswered * 100) / (td.countAnswered * 1 + (td.countUnanswered ? td.countUnanswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.countUnanswered ? td.countUnanswered : 0 }}</td>
              <td>{{ td.countUnanswered ? ((td.countUnanswered * 100) / (td.countUnanswered * 1 + (td.countAnswered ? td.countAnswered * 1 : 0))).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data2Answered ? td.data2Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.data1Answered ? td.data1Answered : 0 }} {{ $t('GENERAL.secend') }}</td>
              <td>{{ td.countLogin ? td.countLogin : 0 }}</td>
              <td>{{ td.countLogout ? td.countLogout : 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- loader -->
    <div class="loader-ctn d-flex align-items-center justify-content-center" style="height: 75vh" v-if="!distribution.details">
      <div class="loader-wait-request"></div>
    </div>
  </div>
</template>

<script>

/** import pinia homeStore*/
import { useHome } from '../js/pinia/home'
import { useGeneral } from '../js/pinia/general'
import { useDistribution } from '../js/pinia/distribution'

// helper
import helper from '../js/helper'

var moment = require('moment-jalaali')

// import chart
import barChart from './chart/BarChart.vue'

// import vue good table
import { VueGoodTable } from 'vue-good-table-next';



export default {
  name: 'distribution',
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

      columnsWaitByDate: [
        {
          label: this.$t('DIS.wait.logout'),
          field: 'countLogout',
          type: 'number',

        },
        {
          label: this.$t('DIS.wait.login'),
          field: 'countLogin',
          type: 'number',

        },
        {
          label: this.$t('DIS.wait.avgWait'),
          field: 'data1Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.wait.avgTime'),
          field: 'data2Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.wait.pUnAnswered'),
          field: 'pUnAnswered',
          sortable: false,

        },

        {
          label: this.$t('DIS.wait.unAnswered'),
          field: 'countUnanswered',
          type: 'number',
        },

        {
          label: this.$t('DIS.wait.pAnswered'),
          field: 'pAnswered',
          sortable: false,
        },
        {
          label: this.$t('DIS.wait.answered'),
          field: 'countAnswered',
          type: 'number',
        },
        {
          label: this.$t('DIS.wait.data'),
          field: 'date',
          sortable: false,

        },
      ],

      columnsWaitByTime: [
        {
          label: this.$t('DIS.dispersion.logout'),
          field: 'countLogout',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersion.login'),
          field: 'countLogin',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersion.avgWait'),
          field: 'data1Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersion.avgTime'),
          field: 'data2Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersion.pUnAnswered'),
          field: 'pUnAnswered',
          sortable: false,

        },

        {
          label: this.$t('DIS.dispersion.unAnswered'),
          field: 'countUnanswered',
          type: 'number',
        },

        {
          label: this.$t('DIS.dispersion.pAnswered'),
          field: 'pAnswered',
          sortable: false,
        },
        {
          label: this.$t('DIS.dispersion.answered'),
          field: 'countAnswered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersion.time'),
          field: 'hour',
          type: 'number',

        },
      ],

      columnsAnsweredInWeek: [
        {
          label: this.$t('DIS.dispersionInWeek.logout'),
          field: 'countLogout',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersionInWeek.login'),
          field: 'countLogin',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersionInWeek.avgWait'),
          field: 'data1Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInWeek.avgTime'),
          field: 'data2Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInWeek.pUnAnswered'),
          field: 'pUnAnswered',
          sortable: false,

        },

        {
          label: this.$t('DIS.dispersionInWeek.unAnswered'),
          field: 'countUnanswered',
          type: 'number',
        },

        {
          label: this.$t('DIS.dispersionInWeek.pAnswered'),
          field: 'pAnswered',
          sortable: false,
        },
        {
          label: this.$t('DIS.dispersionInWeek.answered'),
          field: 'countAnswered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInWeek.day'),
          field: 'day',
          sortable: false,
        },
      ],

      columnsAnsweredInMonth: [
        {
          label: this.$t('DIS.dispersionInMonth.logout'),
          field: 'countLogout',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersionInMonth.login'),
          field: 'countLogin',
          type: 'number',

        },
        {
          label: this.$t('DIS.dispersionInMonth.avgWait'),
          field: 'data1Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInMonth.avgTime'),
          field: 'data2Answered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInMonth.pUnAnswered'),
          field: 'pUnAnswered',
          sortable: false,

        },

        {
          label: this.$t('DIS.dispersionInMonth.unAnswered'),
          field: 'countUnanswered',
          type: 'number',
        },

        {
          label: this.$t('DIS.dispersionInMonth.pAnswered'),
          field: 'pAnswered',
          sortable: false,
        },
        {
          label: this.$t('DIS.dispersionInMonth.answered'),
          field: 'countAnswered',
          type: 'number',
        },
        {
          label: this.$t('DIS.dispersionInMonth.month'),
          field: 'month',
          type: 'number',
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
    /** convert number of day to fa day */
    showDay(day) {
      let faDay = ''
      switch (day) {
        case '0':
          faDay = this.$t('GENERAL.Saturday')
          break;
        case '1':
          faDay = this.$t('GENERAL.Sunday')
          break;
        case '2':
          faDay = this.$t('GENERAL.Monday')
          break;
        case '3':
          faDay = this.$t('GENERAL.Tuesday')
          break;
        case '4':
          faDay = this.$t('GENERAL.Wednesday')
          break;
        case '5':
          faDay = this.$t('GENERAL.Thursday')
          break;
        case '6':
          faDay = this.$t('GENERAL.Friday')
          break;

        default:
          break;
      }
      return faDay
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
          'method': 'distribution_getData',
          'queues': queues,
          'agents': agents,
          'timeFilter': this.home.timeFilter,
          'toFilter': this.home.toFilter ? moment(this.home.toFilter + ' ' + this.home.toTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
          'fromFilter': this.home.fromFilter ? moment(this.home.fromFilter + ' ' + this.home.fromTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
        }
        let req = await this.$axios({
          url: '/distributionActions',
          data: data
        })

        /** merge data feild waitByDate*/
        let objWaitByDate = req.data.waitByDate
        let mergedByDate = this.merge(objWaitByDate.answered, objWaitByDate.Unanswered, 'date');
        mergedByDate = this.merge(objWaitByDate.answered, objWaitByDate.login, 'date');
        mergedByDate = this.merge(objWaitByDate.answered, objWaitByDate.logout, 'date');

        /** merge data feild waitByTime*/
        let objWaitByTime = req.data.waitByTime
        let mergedByTime = this.merge(objWaitByTime.answered, objWaitByTime.Unanswered, 'hour');
        mergedByTime = this.merge(objWaitByTime.answered, objWaitByTime.login, 'hour');
        mergedByTime = this.merge(objWaitByTime.answered, objWaitByTime.logout, 'hour');
        let sort = mergedByTime.sort((a, b) => a.hour - b.hour)
        mergedByTime = sort

        /** merge data feild AnsweredInWeek*/
        let objAnsweredInWeek = req.data.answeredInWeek
        let mergedInWeek = this.merge(objAnsweredInWeek.answered, objAnsweredInWeek.Unanswered, 'day');
        mergedInWeek = this.merge(objAnsweredInWeek.answered, objAnsweredInWeek.login, 'day');
        mergedInWeek = this.merge(objAnsweredInWeek.answered, objAnsweredInWeek.logout, 'day');
        sort = mergedInWeek.sort((a, b) => a.time - b.time)
        mergedInWeek = sort

        /** generat data chart پاسخ داده شده / بدون پاسخ در ساعت */
        let chartAnswered = mergedByTime.map((item) => {
          return {
            'lable': this.$t('GENERAL.hour'),
            [this.$t('DIS.chartAnswered.answered')]: parseInt(item.countAnswered),
            [this.$t('DIS.chartAnswered.unAnswered')]: parseInt(item.countUnanswered),
          }
        })

        // <!--  نمودار میانگین  زمان صحبت در ساعت -->
        let chartTimeAnswered = mergedByTime.map((item) => {
          return {
            'lable': this.$t('GENERAL.hour'),
            [this.$t('DIS.chartTimeAnswered.avgTime')]: parseFloat(item.data2Answered)
          }
        })

        // <!--  نمودار میانگین زمان انتظار در ساعت -->
        let chartDelayAnswered = mergedByTime.map((item) => {
          return {
            'lable': this.$t('GENERAL.hour'),
            [this.$t('DIS.chartDelayAnswered.avgDelay')]: parseFloat(item.data1Answered)

          }
        })

        // نمودار پاسخ به تماس ها براساس روزهای هفته
        let ls = this;
        let chartAnsweredWeek = mergedInWeek.map((item) => {
          return {
            'lable': ls.showDay(item.day),
            [this.$t('DIS.chartAnsweredWeek.count')]: item.countAnswered
          }
        })

        // نمودار میانگین مدت تماس براساس روزهای هفته
        let chartTimeAnsweredWeek = mergedInWeek.map((item) => {
          return {
            'lable': ls.showDay(item.day),
            [this.$t('DIS.chartTimeAnsweredWeek.avgAnswered')]: parseFloat(item.data2Answered)

          }
        })

        /** merge data feild AnsweredInMonth*/
        let objAnsweredInMonth = req.data.answeredInMonth
        let mergedInMonth = this.merge(objAnsweredInMonth.answered, objAnsweredInMonth.Unanswered, 'month');
        mergedInMonth = this.merge(objAnsweredInMonth.answered, objAnsweredInMonth.login, 'month');
        mergedInMonth = this.merge(objAnsweredInMonth.answered, objAnsweredInMonth.logout, 'month');
        sort = mergedInMonth.sort((a, b) => a.time - b.time)
        mergedInMonth = sort

        /**start save req for show in page distribution */
        this.distribution.details = req.data.details
        this.distribution.waitByDate = mergedByDate
        this.distribution.waitByTime = mergedByTime
        this.distribution.chartAnswered = chartAnswered
        this.distribution.chartTimeAnswered = chartTimeAnswered
        this.distribution.chartDelayAnswered = chartDelayAnswered
        this.distribution.answeredInWeek = mergedInWeek
        this.distribution.chartTimeAnsweredWeek = chartTimeAnsweredWeek
        this.distribution.chartAnsweredWeek = chartAnsweredWeek
        this.distribution.answeredInMonth = mergedInMonth
        /**end save req for show in page distribution */

      } catch (error) {
        console.error(error);
      }
      this.isLoading = false;
    },
    /** merge object with object  use for data waitByTime
     * @param 1 and 2 is object
     * @param 3 is string (name of Common feild )
     */
    merge(first, two, baseField) {
      try {
        const mergeByProperty = (target, source, prop) => {
          source.forEach(sourceElement => {
            let targetElement = target.find(targetElement => {
              return sourceElement[prop] === targetElement[prop];
            })
            targetElement ? Object.assign(targetElement, sourceElement) : target.push(sourceElement);
          })
        }
        mergeByProperty(first, two, baseField);
        return first

      } catch (error) {
        console.error(error);
      }
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
    const distribution = useDistribution()
    const general = useGeneral()

    return {
      home,
      distribution,
      general,
    }
  }
}

</script>

<style lang='scss'>
#distribution {
}
</style>