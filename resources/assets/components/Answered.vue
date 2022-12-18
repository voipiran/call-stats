<template>
  <div id="answered" class="app">
    <notifications position="bottom left" :duration="5000" />

    <!-- {{-- refresh btn --}} -->
    <div class="refresh-div">
      <div
        @click="
          answered.details = [];
          resetTime();
          getData();
          answeredCallsDetail();
        "
        class="refresh"
      ></div>
    </div>

    <!-- content -->
    <div class="container-fluid" v-if="answered.details.length">
      <h3 class="my-2">{{ $t('ANS.title') }}</h3>

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
        <div class="col-12 col-md-6" v-if="answered.details.length">
          <h5>{{ $t('ANS.detail.title') }}</h5>
          <ul>
            <li>
              <span>{{ $t('ANS.detail.answered') }}</span
              ><span>{{ answered.details[0].count ? answered.details[0].count : '' }} {{ $t('GENERAL.call') }}</span>
            </li>
            <!-- <li><span>تماس های انتقال داده شده :</span><span></span></li> -->
            <li>
              <span> {{ $t('ANS.detail.avgTime') }}</span
              ><span v-html="answered.details[0].time ? secondsToDay(answered.details[0].time / answered.details[0].count) : ''"></span>
            </li>
            <li>
              <span> {{ $t('ANS.detail.time') }}</span
              ><span v-html="answered.details[0].time ? secondsToDay(answered.details[0].time) : ''"> </span>
            </li>
            <li>
              <span> {{ $t('ANS.detail.avgDelay') }}</span
              ><span v-html="answered.details[0].delay ? secondsToDay(answered.details[0].delay / answered.details[0].count) : ''"> </span>
            </li>
          </ul>
        </div>
      </div>

      <!-- تماس های پاسخ داده شده توسط اپراتور -->
      <div class="table-shadow row">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.agent.GUIDE')">
              <p>{{ $t('ANS.agent.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.agent.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('ANSWERED_answeredTbl', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('ANSWERED_answeredTbl', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('ANSWERED_answeredTbl', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!--vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsAnswered" :rows="answered.answered" :search-options="optionsTable">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'pCalls'">
                <span dir="rtl">{{ ((props.row.count * 100) / answered.details[0].count).toFixed(2) }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'pTime'">
                <span dir="rtl">{{ ((props.row.time * 100) / answered.details[0].time).toFixed(2) }} {{ $t('GENERAL.percentage') }}</span>
              </span>
              <span v-else-if="props.column.field == 'avgTime'">
                <span dir="rtl">
                  {{ secondsToDay((props.row.time / props.row.count).toFixed(2)) }}
                </span>
              </span>
              <span v-else-if="props.column.field == 'avgWait'">
                <span dir="rtl">{{ secondsToDay((props.row.delay / props.row.count).toFixed(2)) }}</span>
              </span>
              <span v-else-if="props.column.field == 'delay'">
                <span dir="rtl">{{ secondsToDay(props.row.delay) }}</span>
              </span>
              <span v-else-if="props.column.field == 'time'">
                <span dir="rtl">{{ secondsToDay(props.row.time) }}</span>
              </span>

              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- the page content for export -->
        <table class="mt-3" id="ANSWERED_answeredTbl" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('ANS.agent.agent') }}</th>
              <th>{{ $t('ANS.agent.received') }}</th>
              <th>{{ $t('ANS.agent.pCalls') }}</th>
              <th>{{ $t('ANS.agent.time') }}</th>
              <th>{{ $t('ANS.agent.pTime') }}</th>
              <th>{{ $t('ANS.agent.avgTime') }}</th>
              <th>{{ $t('ANS.agent.wait') }}</th>
              <th>{{ $t('ANS.agent.avgWait') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in answered.answered" :key="indexTd">
              <td>{{ td.agent }}</td>
              <td>{{ td.count }}</td>
              <td>{{ ((td.count * 100) / answered.details[0].count).toFixed(2) }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ secondsToDay(td.time) }}</td>
              <td>{{ ((td.time * 100) / answered.details[0].time).toFixed(2) }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ secondsToDay((td.time / td.count).toFixed(2)) }}</td>
              <td>{{ secondsToDay(td.delay) }}</td>
              <td>{{ secondsToDay((td.delay / td.count).toFixed(2)) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- سطح سرویس -->
      <div class="table-shadow row" v-if="answered.service.length">
        <!-- table -->
        <div class="col-12 col-md-6">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.service.GUIDE')">
              <p>{{ $t('ANS.service.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.service.title') }}</h5>
          </div>

          <!--vue good table -->
          <div class="w-100" dir="ltr">
            <vue-good-table :columns="columnsService" :rows="answered.service">
              <!-- customize fields  -->
              <template #table-row="props">
                <span v-if="props.column.field == 'pCount'">
                  <span>{{ maxService ? ((props.row.count * 100) / maxService).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</span>
                </span>
                <span v-else-if="props.column.field == 'count'">
                  <span>{{ props.row.count }} +</span>
                </span>
                <span v-else-if="props.column.field == 'countAll'">
                  <span>{{ props.row.sum }} {{ $t('GENERAL.call') }}</span>
                </span>
                <span v-else-if="props.column.field == 'lable'">
                  <span>{{ $t('ANS.service.lessThan') }} {{ secondsToDay(props.row.lable, false, 'table') }} </span>
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
          <barChart :data="answered.service"></barChart>
        </div>
      </div>

      <!-- تماس های پاسخ داده شده توسط صف -->
      <div class="table-shadow row" v-if="answered.queueAnswered.length">
        <!-- table -->
        <div class="col-12 col-md-6">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.queue.GUIDE')">
              <p>{{ $t('ANS.queue.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.queue.title') }}</h5>
          </div>

          <!--vue good table -->
          <div class="w-100" dir="ltr">
            <vue-good-table :columns="columnsQueueAnswered" :rows="answered.queueAnswered">
              <!-- customize fields  -->
              <template #table-row="props">
                <span v-if="props.column.field == 'count'">
                  <span>{{ props.row.count }} {{ $t('GENERAL.call') }}</span>
                </span>
                <span v-else-if="props.column.field == 'pCalls'">
                  <span>{{ ((props.row.count * 100) / answered.details[0].count).toFixed(2) }} {{ $t('GENERAL.percentage') }}</span>
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
          <barChart :data="answered.queueAnswered"></barChart>
        </div>
      </div>

      <!-- دلیل قطع شدن مکالمه -->
      <div class="table-shadow row" v-if="answered.hangUp.length">
        <!-- table -->
        <div class="col-12 col-md-6">
          <!-- title -->
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.disconnection.GUIDE')">
              <p>{{ $t('ANS.disconnection.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.disconnection.title') }}</h5>
          </div>

          <!-- the page content  -->
          <table class="mt-3">
            <thead>
              <tr>
                <th>{{ $t('ANS.disconnection.event') }}</th>
                <th>{{ $t('ANS.disconnection.received') }}</th>
                <th>{{ $t('ANS.disconnection.pCount') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(td, indexTd) in answered.hangUp" :key="indexTd">
                <td>{{ $t('ANS.disconnection.hangUp') }} {{ td.lable }}</td>
                <td>{{ td.count }} {{ $t('GENERAL.call') }}</td>
                <td>{{ answered.details[0].count != '0' ? ((td.count * 100) / answered.details[0].count).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- chart -->
        <div class="col-12 col-md-6">
          <barChart :data="answered.hangUp"></barChart>
        </div>
      </div>

      <!-- تماس های پاسخ داده شده بر اساس مدت مکالمه -->
      <div class="table-shadow row" v-if="answered.answeredByCallLength">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.byTime.GUIDE')">
              <p>{{ $t('ANS.byTime.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.byTime.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('ANSWERED_answeredByCallLength', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('ANSWERED_answeredByCallLength', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('ANSWERED_answeredByCallLength', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>

        <!-- the page content for export  -->
        <table class="mt-3" id="ANSWERED_answeredByCallLength" v-show="true">
          <thead>
            <tr>
              <th>{{ $t('ANS.byTime.duration') }}</th>
              <th>{{ $t('ANS.byTime.received') }}</th>
              <th>{{ $t('ANS.byTime.completed') }}</th>
              <th>{{ $t('ANS.byTime.transferred') }}</th>
              <th>{{ $t('ANS.byTime.pCount') }}</th>
              <th>{{ $t('ANS.byTime.time') }}</th>
              <th>{{ $t('ANS.byTime.pTime') }}</th>
              <th>{{ $t('ANS.byTime.avgTime') }}</th>
              <th>{{ $t('ANS.byTime.wait') }}</th>
              <th>{{ $t('ANS.byTime.avgWait') }}</th>
              <th>{{ $t('ANS.byTime.maxWait') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in answered.answeredByCallLength" :key="indexTd">
              <td>{{ secondsToDay(td.lable, false, 'table') }}</td>
              <td>{{ td.data[0].count * 1 + td.data[1].count * 1 }}</td>
              <td>{{ td.data[0].count }}</td>
              <td>{{ td.data[1].count }}</td>
              <td>{{ td.data[0].count ? ((td.data[0].count * 100) / answered.details[0].count).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data[0].time ? secondsToDay(td.data[0].time, false, 'table') : 0 }}</td>
              <td>{{ td.data[0].time ? ((td.data[0].time * 100) / answered.details[0].time).toFixed(2) : 0 }} {{ $t('GENERAL.percentage') }}</td>
              <td>{{ td.data[0].time ? secondsToDay((td.data[0].time / td.data[0].count).toFixed(2), false, 'table') : 0 }}</td>
              <td>{{ td.data[0].delay ? secondsToDay(td.data[0].delay, false, 'table') : 0 }}</td>
              <td>{{ td.data[0].delay ? secondsToDay((td.data[0].delay / td.data[0].count).toFixed(2), false, 'table') : 0 }}</td>
              <td>{{ td.data[0].maxDelay ? secondsToDay(td.data[0].maxDelay, false, 'table') : 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- تماس های منتقل شده -->
      <div class="table-shadow row" v-if="answered.answeredTransfer">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.transfers.GUIDE')">
              <p>{{ $t('ANS.transfers.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.transfers.title') }}</h5>
          </div>
          <div class="export">
            <div class="pdf" @click="tableExport('ANSWERED_answeredTransfer', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="tableExport('ANSWERED_answeredTransfer', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="tableExport('ANSWERED_answeredTransfer', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
        </div>
        <!-- vue-good-table -->
        <vue-good-table class="w-100" dir="ltr" :columns="columnsAnsweredTransfer" :rows="answered.answeredTransfer"> </vue-good-table>

        <!-- the page content for export -->
        <table class="mt-3" id="ANSWERED_answeredTransfer" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('ANS.transfers.agent') }}</th>
              <th>{{ $t('ANS.transfers.to') }}</th>
              <th>{{ $t('ANS.transfers.count') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(td, indexTd) in answered.answeredTransfer" :key="indexTd">
              <td>{{ td.agent }}</td>
              <td></td>
              <td>{{ td.count }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- جزئیات تماس های پاسخ داده شده -->
      <div class="table-shadow row" v-if="answered.answeredCallsDetail">
        <!-- title -->
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex">
            <div class="guide" v-if="$t('ANS.ansDetail.GUIDE')">
              <p>{{ $t('ANS.ansDetail.GUIDE') }}</p>
            </div>
            <h5 class="m-0">{{ $t('ANS.ansDetail.title') }}</h5>
          </div>
          <div class="export" v-if="!isLoadingExport">
            <div class="pdf" @click="getDataForExport('ANSWERED_answeredCallsDetail', 'pdf')" :title="$t('GENERAL.pdfExport')"></div>
            <div class="excel" @click="getDataForExport('ANSWERED_answeredCallsDetail', 'xls')" :title="$t('GENERAL.excelExport')"></div>
            <div class="csv" @click="getDataForExport('ANSWERED_answeredCallsDetail', 'csv')" :title="$t('GENERAL.csvExport')"></div>
          </div>
          <div class="loader-wait-request mx-2" style="width: 20px; height: 20px" v-if="isLoadingExport"></div>
        </div>

        <!-- vue good table -->
        <div class="w-100" dir="ltr">
          <vue-good-table :columns="columnsAnsweredCallsDetail" :rows="answered.answeredCallsDetail" :search-options="optionsTable" :pagination-options="paginationOptions">
            <!-- customize fields  -->
            <template #table-row="props">
              <span v-if="props.column.field == 'time'">
                <span v-if="$i18n.locale == 'en'">{{ jdate(props.row.time, 'YYYY/MM/DD HH:mm') }}</span>
                <span v-else>{{ jdate(props.row.time, 'jYYYY/jMM/jDD HH:mm') }}</span>
              </span>

              <span v-else-if="props.column.field == 'hurs'">
                <span>{{ jdate(props.row.time, 'HH:mm') }}</span>
              </span>
              <span v-else-if="props.column.field == 'data1'">
                <span dir="rtl">{{ secondsToDay(props.row.data1, false, 'table') }} </span>
              </span>
              <span v-else-if="props.column.field == 'data2'">
                <span dir="rtl">{{ secondsToDay(props.row.data2, false, 'table') }} </span>
              </span>
              <span class="voice" v-else-if="props.column.field == 'voice'" @click="getVoice(props.row.voice)">
                <svg v-if="voiceLoading != props.row.voice && !props.row.file" xmlns="http://www.w3.org/2000/svg" width="16.015" height="15.003" viewBox="0 0 16.015 15.003" fill="gray">
                  <path
                    id="download"
                    d="M70.19,101.521a.46.46,0,0,0,.639,0l5.385-5.231a.431.431,0,0,0,0-.621l-1.071-1.04a.46.46,0,0,0-.639,0L71.956,97.1V90.439A.446.446,0,0,0,71.5,90H69.7a.446.446,0,0,0-.452.439v6.848l-2.735-2.658a.46.46,0,0,0-.639,0L64.8,95.669a.431.431,0,0,0,0,.621ZM76.293,99v3.78H64.722V99H62.5v4.893A1.11,1.11,0,0,0,63.613,105H77.4a1.111,1.111,0,0,0,1.113-1.111V99H76.293Z"
                    transform="translate(-62.5 -90)"
                  />
                </svg>
                <!-- loader -->
                <div v-else>
                  <div class="loader-ctn d-flex align-items-center justify-content-center" v-if="voiceLoading == props.row.voice && !props.row.file">
                    <div class="loader-wait-request" style="height: 20px; width: 20px"></div>
                  </div>
                  <!-- if voice exist -->
                  <div v-if="props.row.file != 'empty' && voiceLoading != props.row.voice">
                    <audio controls>
                      <source :src="`monitor/${props.row.pathFile}/${props.row.file}`" type="audio/mpeg" />
                      Your browser does not support the audio element.
                    </audio>
                  </div>
                  <!-- if not exist -->
                  <div v-if="props.row.file == 'empty' && voiceLoading != props.row.voice">
                    {{ $t('ANS.ansDetail.NotVoice') }}
                  </div>
                </div>
              </span>
              <span v-else>
                {{ props.formattedRow[props.column.field] }}
              </span>
            </template>
          </vue-good-table>
        </div>

        <!-- table  for export-->
        <table class="mt-4" id="ANSWERED_answeredCallsDetail" v-show="false">
          <thead>
            <tr>
              <th>{{ $t('ANS.ansDetail.duration') }}</th>
              <th>{{ $t('ANS.ansDetail.queue') }}</th>
              <th>{{ $t('ANS.ansDetail.agent') }}</th>
              <th>{{ $t('ANS.ansDetail.event') }}</th>
              <th>{{ $t('ANS.ansDetail.ringTime') }}</th>
              <th>{{ $t('ANS.ansDetail.wait') }}</th>
              <th>{{ $t('ANS.ansDetail.time') }}</th>
            </tr>
          </thead>
          <tbody>
            <!-- content -->
            <tr v-for="(td, index) in rowsExport" :key="index">
              <td v-if="$i18n.locale == 'en'">{{ jdate(td.time, 'YYYY/MM/DD') }}</td>
              <td v-else>{{ jdate(td.time, 'jYYYY/jMM/jDD') }}</td>
              <td>{{ td.queue }}</td>
              <td>{{ td.agent }}</td>
              <td>{{ td.event ? $t(`GENERAL.${td.event}`) : '' }}</td>
              <td>{{ jdate(td.time, 'HH:mm') }}</td>
              <td>{{ secondsToDay(td.data1, false, 'table') }}</td>
              <td>{{ secondsToDay(td.data2, false, 'table') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- loader -->
    <div class="loader-ctn d-flex align-items-center justify-content-center" style="height: 75vh" v-if="!answered.details.length">
      <div class="loader-wait-request"></div>
    </div>
  </div>
</template>

<script>

/** import pinia homeStore*/
import { useHome } from '../js/pinia/home'
import { useGeneral } from '../js/pinia/general'
import { useAnswered } from '../js/pinia/answered'

// helper
import helper from '../js/helper'

var moment = require('moment-jalaali')

// import chart
import barChart from './chart/BarChart.vue'

// import vue good table
import { VueGoodTable } from 'vue-good-table-next';


export default {
  name: 'answered',
  mixins: [helper],
  data() {
    return {
      isLoading: false,
      voiceLoading: false,

      isLoadingExport: false,
      rowsExport: null,

      page: 1,
      perPage: 10,
      totalRecords: 0,

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

      columnsAnswered: [
        {
          label: this.$t('ANS.agent.avgWait'),
          field: 'avgWait',
          sortable: false,

        },
        {
          label: this.$t('ANS.agent.wait'),
          field: 'delay',
          type: 'number',
        },
        {
          label: this.$t('ANS.agent.avgTime'),
          field: 'avgTime',
          sortable: false,
        },
        {
          label: this.$t('ANS.agent.pTime'),
          field: 'pTime',
          sortable: false,

        },

        {
          label: this.$t('ANS.agent.time'),
          field: 'time',
          type: 'number',
        },

        {
          label: this.$t('ANS.agent.pCalls'),
          field: 'pCalls',
          sortable: false,
        },
        {
          label: this.$t('ANS.agent.received'),
          field: 'count',
          type: 'number',
        },
        {
          label: this.$t('ANS.agent.agent'),
          field: 'agent',
        },
      ],

      columnsService: [

        {
          label: this.$t('ANS.service.pCount'),
          field: 'pCount',
          sortable: false,
        },
        {
          label: this.$t('ANS.service.delta'),
          field: 'count',
          type: 'number',
        },
        {
          label: this.$t('ANS.service.count'),
          field: 'countAll',
          sortable: false,
        },

        {
          label: this.$t('ANS.service.answered'),
          field: 'lable',
          type: 'number',
        },
      ],
      maxService: 0,

      columnsQueueAnswered: [

        {
          label: this.$t('ANS.queue.pCalls'),
          field: 'pCalls',
          sortable: false,
        },
        {
          label: this.$t('ANS.queue.received'),
          field: 'count',
          type: 'number',
        },
        {
          label: this.$t('ANS.queue.queue'),
          field: 'lable',
          type: 'number',
        },

      ],


      columnsAnsweredCallsDetail: [

        {
          label: this.$t('ANS.ansDetail.voice'),
          field: 'voice',
          sortable: false,
        },
        {
          label: this.$t('ANS.ansDetail.phone'),
          field: 'phone',
          sortable: false,
        },
        {
          label: this.$t('ANS.ansDetail.time'),
          field: 'data2',
          type: 'number',
        },
        {
          label: this.$t('ANS.ansDetail.wait'),
          field: 'data1',
          type: 'number',
        },
        // {
        //   label: this.$t('ANS.ansDetail.ringTime'),
        //   field: 'hurs',
        //   sortable: false,

        // },

        {
          label: this.$t('ANS.ansDetail.event'),
          field: 'event',
        },

        {
          label: this.$t('ANS.ansDetail.agent'),
          field: 'agent',
          sortable: true,
        },
        {
          label: this.$t('ANS.ansDetail.queue'),
          field: 'queuename',
          type: 'number',
        },

        {
          label: this.$t('ANS.ansDetail.duration'),
          field: 'time',
        }
      ],

      columnsAnsweredTransfer: [
        {
          label: this.$t('ANS.transfers.count'),
          field: 'count',
          type: 'number',

        },

        {
          label: this.$t('ANS.transfers.to'),
          field: 'to',
          sortable: false,
        },

        {
          label: this.$t('ANS.transfers.agent'),
          field: 'agent',
        },
      ],
    }
  },
  methods: {
    /** download voice via callid */
    async getVoice(callid) {
      try {
        this.voiceLoading = callid
        let req = await this.$axios({
          url: '/answeredActions',
          data: { callid: callid, method: 'getVoice' }
        })

        let update = this.answered.answeredCallsDetail.map((item) => {
          if (item.voice == callid)
            return {
              file: req.data.file,
              pathFile: `${this.jdate(item.time, 'YYYY')}/${this.jdate(item.time, 'MM')}/${this.jdate(item.time, 'DD')}`,
              agent: item.agent,
              data1: item.data1,
              data2: item.data2,
              data3: item.data3,
              phone: item.phone,
              event: item.event,
              queuename: item.queuename,
              voice: item.voice,
              time: item.time,

            }
          return item
        });
        console.log('update: ', update);
        this.answered.answeredCallsDetail = update
      } catch (error) {
        console.log(error);
      }
      this.voiceLoading = false
    },

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
          'method': 'Answered_getData',
          'queues': queues,
          'agents': agents,
          'timeFilter': this.home.timeFilter,
          'toFilter': this.home.toFilter ? moment(this.home.toFilter + ' ' + this.home.toTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
          'fromFilter': this.home.fromFilter ? moment(this.home.fromFilter + ' ' + this.home.fromTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
        }
        let req = await this.$axios({
          url: '/answeredActions',
          data: data
        })
        /**start save req for show in page answered */
        this.answered.answered = req.data.answered
        this.answered.details = req.data.details


        let max = 0;
        let sum = 0;
        let service = req.data.service.map((item) => {
          if (item.count > max)
            max = item.count;
          sum += item.count;
          return {
            count: item.count,
            sum: sum,
            lable: item.lable
          }
        })
        this.answered.service = service;
        this.maxService = max;

        this.answered.queueAnswered = req.data.qAnswered
        this.answered.hangUp = req.data.hangUp.map((item) => {
          return {
            lable: this.$t(`ANS.disconnection.${item.lable}`),
            count: item.count
          }
        })
        this.answered.answeredByCallLength = req.data.answeredByCallLength
        this.answered.answeredTransfer = req.data.answeredTransfer

        // /** for show date in top all components */
        // this.home.fromFilterFaLable = this.jdate(req.data.timeFilter[0], 'jYYYY/jMM/jDD')
        // this.home.toFilterFaLable = this.jdate(req.data.timeFilter[1], 'jYYYY/jMM/jDD')
        // /**end save req for show in page answered */



      } catch (error) {
        console.error(error);
      }
      this.isLoading = false;
      this.answeredCallsDetail()
    },

    /** Answered Calls Detail */
    async answeredCallsDetail(pagination = null, exportRequest = false) {
      try {
        /** back to home component */
        if (!this.home.queues.length) return this.general.route = '/home'

        if (this.isLoading) return

        if (!exportRequest) {
          if (pagination == 'next') this.page++
          else if (pagination == 'back') this.page--
          else this.page = 1
        }

        if (this.page <= 1)
          this.page = 1;


        this.isLoading = true;


        let agents = []
        this.home.agents.map((item) => { return agents.push(item.code) })
        let queues = []
        this.home.queues.map((item) => { return queues.push(item.code) })


        let data = {
          'page': this.page,
          'perPage': this.perPage,
          'method': 'Answered_getAnsweredCallsDetail',
          'queues': queues,
          'agents': agents,
          'timeFilter': this.home.timeFilter,
          'toFilter': this.home.toFilter ? moment(this.home.toFilter + ' ' + this.home.toTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
          'fromFilter': this.home.fromFilter ? moment(this.home.fromFilter + ' ' + this.home.fromTime, 'jYYYY/jM/jD HH:mm').format('YYYY-MM-DD HH:mm') : null,
          'export': exportRequest

        }
        let req = await this.$axios({
          url: '/answeredActions',
          data: data
        })

        if (exportRequest)
          return this.rowsExport = req.data.data


        let report = await this.merge(req.data.data, req.data.mobile, 'callid');

        this.answered.answeredCallsDetail = report.map((item) => {
          return {
            agent: item.agent,
            data1: item.data1,
            data2: item.data2,
            data3: item.data3,
            phone: item.phone,
            event: this.$t(`GENERAL.${item.event}`),
            queuename: item.queuename,
            voice: `${item.callid}`,
            time: item.time,

          }
        })


      } catch (error) {
        console.error(error);
      }
      this.isLoading = false;

    },

    /** request export
 * @param1 is string for detect type file (pdf,scv,...)
 */
    async getDataForExport(idTable, kind) {
      try {
        this.isLoadingExport = true;
        await this.answeredCallsDetail(null, true)
        this.tableExport(idTable, kind)
        this.isLoadingExport = false;

      } catch (error) {
        console.error(error);
      }
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
  async mounted() {
    await this.getData()
  },
  setup() {
    const home = useHome()
    const answered = useAnswered()
    const general = useGeneral()

    return {
      general,
      home,
      answered
    }
  }
}
</script>

<style lang='scss'>
#answered {
  .voice {
    cursor: pointer;
  }
}
</style>