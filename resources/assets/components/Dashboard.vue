<template>
  <div id="home" class="app">
    <notifications position="bottom left" :duration="5000" />

    <!--state overview start-->
    <div class="row state-overview">
      <div class="col-lg-3 col-sm-6">
        <section class="card">
          <div class="symbol terques">
            <i class="fa fa-user"></i>
          </div>
          <div class="value">
            <h1 class="count">0</h1>
            <p>New Users</p>
          </div>
        </section>
      </div>
      <div class="col-lg-3 col-sm-6">
        <section class="card">
          <div class="symbol red">
            <i class="fa fa-tags"></i>
          </div>
          <div class="value">
            <h1 class="count2">0</h1>
            <p>Sales</p>
          </div>
        </section>
      </div>
      <div class="col-lg-3 col-sm-6">
        <section class="card">
          <div class="symbol yellow">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="value">
            <h1 class="count3">0</h1>
            <p>New Order</p>
          </div>
        </section>
      </div>
      <div class="col-lg-3 col-sm-6">
        <section class="card">
          <div class="symbol blue">
            <i class="fa fa-bar-chart-o"></i>
          </div>
          <div class="value">
            <h1 class="count4">0</h1>
            <p>Total Profit</p>
          </div>
        </section>
      </div>
    </div>
    <!--state overview end-->

    <div id="answered" v-if="false">
      <notifications position="bottom left" :duration="5000" />
      <div class="container-fluid">
        <h3>وضعیت جاری اپراتور</h3>
        <p class="aent-hide-stateg">
          <input type="checkbox" v-model="agentHideState" name="" id="" />
          <span @click="agentHideState = !agentHideState"> مخفی کردن اپراتورهای غیرفعال </span>
        </p>
        <!-- agents table -->
        <div class="table-shadow my-3">
          <h4>وضعیت اپراتور</h4>
          <!-- the page content  -->
          <div v-if="agents.length">
            <table v-for="(table, indexTable) in agents" :key="indexTable" class="my-5">
              <thead>
                <tr>
                  <th>صف</th>
                  <th>نام اپراتور</th>
                  <th>وضعیت</th>
                  <th>مدت زمان</th>
                  <th>CLID</th>
                  <th>زمان آخرین مکالمه</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(td, indexTd) in table" :key="indexTd" v-show="!agentHideState && !td.state">
                  <td>{{ td.queue }}</td>
                  <td>{{ td.agent }}</td>
                  <td>{{ td.state }}</td>
                  <td>{{ numberWithCommas(td.data1) }}</td>
                  <td></td>
                  <td>{{ jdate(td.lastCall) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- no content -->
          <div v-if="!agents.length">
            <span class="px-2">فاقد محتوا</span>
          </div>
        </div>
        <!-- detail of queue -->
        <div class="table-shadow my-3">
          <h4>خلاصه آمار صف</h4>
          <!-- the page content  -->
          <div v-if="queues.length">
            <table v-for="(table, indexTable) in queues" :key="indexTable" class="my-5">
              <thead>
                <tr>
                  <th>صف</th>
                  <th>اپراتور آزاد</th>
                  <th>درحال مکالمه</th>
                  <th>درحال مکالمه</th>
                  <th>افراد منتظر</th>
                  <th>قدیمی ترین فرد منتظر</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(td, indexTd) in table" :key="indexTd">
                  <td>{{ td.queue }}</td>
                  <td>{{ td.agent }}</td>
                  <td></td>
                  <td>{{ td.data1 }}</td>
                  <td></td>
                  <td>{{ jdate(td.lastCall) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- no content -->
          <div v-if="!queues.length">
            <span class="px-2">فاقد محتوا</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>


// helper
import helper from '../js/helper'

export default {
  name: 'answered',
  mixins: [helper],
  data() {
    return {
      isLoading: false,
      agents: [],
      queues: [],

      agentHideState: false,
    }
  },
  methods: {
    /** get data from api */
    async getData() {
      try {

        this.isLoading = true

        let req = await this.$axios({
          url: '/allActions',
          data: {
            'method': 'Dashboard_getData'
          }
        })

        /** splite and sort by Queue id */
        let ls = this
        req.data.queues.map((queue) => {
          let sortQueue = []
          req.data.agents.map((item) => {
            if (queue.qname_id == item.qname_id)
              sortQueue.push(item)
          })
          if (sortQueue.length)
            ls.agents.push(sortQueue)
        })

      } catch (error) {
        console.log(error);
      }
      this.isLoading = false
    },

  },

  mounted() {
    this.getData()

  }
}
</script>

<style lang='scss'>
/** use in all component Except search component */
.app {
  ul {
    padding: 0;
    margin-top: 20px;
    background-color: #dfdfdf;
    li:nth-child(odd) {
      background-color: #f6f6f6;
    }
    li:nth-child(even) {
      background-color: #ededed;
    }
    li {
      padding: 10px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }
  }
}

/** ---------------- start css for all component -----------------------*/
.table-shadow {
  box-shadow: 3px 12px 13px #b9b9b978;
  margin-top: 50px !important;
  padding: 20px;
  overflow: auto;
}
//** loader-wait-request */

/** --------- start pdf export icon and guid and .... */
.guide {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 58 58'%3E%3Cg id='Group_538' data-name='Group 538' transform='translate(7203 -1675)'%3E%3Ccircle id='Ellipse_420' data-name='Ellipse 420' cx='29' cy='29' r='29' transform='translate(-7203 1675)' fill='%2300c0fe'/%3E%3Cpath id='Path_10821' data-name='Path 10821' d='M388.925-5.385s29.07.235 28.93 28.978S388.935 52.6 388.935 52.6V24.807Z' transform='translate(-7562.942 1680.385)' fill='%2307a8ee'/%3E%3Cg id='Group_537' data-name='Group 537' transform='translate(-7180.896 1688.669)'%3E%3Ccircle id='Ellipse_421' data-name='Ellipse 421' cx='4.774' cy='4.774' r='4.774' transform='translate(2.053 0.032)' fill='%23e4f7fe'/%3E%3Cpath id='Path_10822' data-name='Path 10822' d='M388.925-5.385s4.8.039 4.78 4.788S388.927 4.2 388.927 4.2V-.4Z' transform='translate(-382.104 5.385)' fill='%23ccedfe'/%3E%3Cg id='Group_536' data-name='Group 536' transform='translate(6.879 12.147)'%3E%3Crect id='Rectangle_705' data-name='Rectangle 705' width='4.62' height='15.4' fill='%23ccedfe'/%3E%3Crect id='Rectangle_704' data-name='Rectangle 704' width='6.879' height='3.08' transform='translate(0 15.275)' fill='%23ccedfe'/%3E%3C/g%3E%3Cg id='Group_535' data-name='Group 535' transform='translate(0 12.147)'%3E%3Crect id='Rectangle_706' data-name='Rectangle 706' width='4.62' height='15.4' transform='translate(2.259)' fill='%23e4f7fe'/%3E%3Crect id='Rectangle_702' data-name='Rectangle 702' width='6.879' height='3.08' transform='translate(0 15.275)' fill='%23e4f7fe'/%3E%3Crect id='Rectangle_703' data-name='Rectangle 703' width='2.053' height='3.08' transform='translate(0.331)' fill='%23e4f7fe'/%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right;
  position: relative;
  min-width: 20px;
  margin-right: 20px;
  padding: 0 15px;

  cursor: pointer;
  p {
    position: absolute;
    background: white;
    right: 0;
    top: 40px;
    width: 300px;
    color: black;
    display: none;
    padding: 10px;
    line-height: 22px;
    text-align: justify;
    border-radius: 7px;
    box-shadow: 3px 12px 13px #b9b9b978;
    font-size: 12px;
    z-index: 1000;
  }
  &:hover {
    p {
      display: block;
    }
  }
}
.export {
  display: flex;
  .pdf {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='20' viewBox='0 0 15 20'%3E%3Cg id='_7267716_ext_pdf_adobe_reader_acrobat_icon' data-name='7267716_ext_pdf_adobe_reader_acrobat_icon' transform='translate(-4.5 -2)'%3E%3Cpath id='Path_1' data-name='Path 1' d='M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19A3 3 0 0 1 16.5 22Z' fill='%23f44336'/%3E%3Cpath id='Path_2' data-name='Path 2' d='M18.8 7.74H15.2a1.5 1.5 0 0 1-1.5-1.5V2.64a.55.55 0 0 1 .94-.39L19.19 6.8a.55.55 0 0 1-.39.94Z' fill='%23ff8a80'/%3E%3Cpath id='Path_3' data-name='Path 3' d='M7.89 19.13a.45.45 0 0 1-.51-.51V15.69a.45.45 0 0 1 .5-.51.45.45 0 0 1 .5.43.78.78 0 0 1 .35-.32 1.07 1.07 0 0 1 .51-.12 1.17 1.17 0 0 1 .64.18 1.2 1.2 0 0 1 .43.51 2 2 0 0 1 0 1.57A1.2 1.2 0 0 1 8.75 18a.86.86 0 0 1-.35-.3v.91a.5.5 0 0 1-.13.38A.52.52 0 0 1 7.89 19.13Zm1-1.76a.48.48 0 0 0 .38-.18.81.81 0 0 0 .14-.55.82.82 0 0 0-.14-.55.5.5 0 0 0-.38-.17.51.51 0 0 0-.39.17.89.89 0 0 0-.14.55.87.87 0 0 0 .14.55.48.48 0 0 0 .42.18Z' fill='%23ffebee'/%3E%3Cpath id='Path_4' data-name='Path 4' d='M12.17 18.11a1.1 1.1 0 0 1-.63-.17 1.22 1.22 0 0 1-.44-.51 2 2 0 0 1 0-1.57 1.22 1.22 0 0 1 .44-.51 1.11 1.11 0 0 1 .63-.18 1.06 1.06 0 0 1 .5.12.91.91 0 0 1 .35.28V14.48a.45.45 0 0 1 .51-.51.49.49 0 0 1 .37.13.5.5 0 0 1 .13.38v3.11a.5.5 0 0 1-1 .08.76.76 0 0 1-.34.32A1.14 1.14 0 0 1 12.17 18.11Zm.33-.74a.48.48 0 0 0 .38-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.5.5 0 0 0-.38-.17.49.49 0 0 0-.38.17.82.82 0 0 0-.15.55.8.8 0 0 0 .15.55.46.46 0 0 0 .38.18Z' fill='%23ffebee'/%3E%3Cpath id='Path_5' data-name='Path 5' d='M15.52 18.1a.46.46 0 0 1-.51-.51V16h-.15a.34.34 0 0 1-.39-.38c0-.25.13-.37.39-.37H15a1.2 1.2 0 0 1 .34-.87 1.52 1.52 0 0 1 .92-.36h.17a.39.39 0 0 1 .29 0 .35.35 0 0 1 .15.17.55.55 0 0 1 0 .22.38.38 0 0 1-.09.19.27.27 0 0 1-.18.1h-.08a.66.66 0 0 0-.41.12.41.41 0 0 0-.11.31v.09h.32c.26 0 .39.12.39.37a.34.34 0 0 1-.39.38H16v1.6a.45.45 0 0 1-.48.53Z' fill='%23ffebee'/%3E%3C/g%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: cover;
    width: 23px;
    height: 30px;
    margin-left: 7px;
    cursor: pointer;
    &:hover {
      height: 33px;
      width: 25px;
    }
  }
  .csv {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='20' viewBox='0 0 15 20'%3E%3Cg id='_7267724_ext_csv_file_document_format_icon_2_' data-name='7267724_ext_csv_file_document_format_icon (2)' transform='translate(-4.5 -2)'%3E%3Cpath id='Path_1' data-name='Path 1' d='M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19A3 3 0 0 1 16.5 22Z' fill='%234caf50'/%3E%3Cpath id='Path_2' data-name='Path 2' d='M18.8 7.74H15.2a1.5 1.5 0 0 1-1.5-1.5V2.64a.55.55 0 0 1 .94-.39L19.19 6.8a.55.55 0 0 1-.39.94Z' fill='%23b9f6ca'/%3E%3Cpath id='Path_3' data-name='Path 3' d='M9.19 18.11a1.77 1.77 0 0 1-.82-.17 1.29 1.29 0 0 1-.55-.51 1.54 1.54 0 0 1-.2-.79 1.52 1.52 0 0 1 .2-.79 1.21 1.21 0 0 1 .55-.5 1.77 1.77 0 0 1 .82-.18 1.45 1.45 0 0 1 .35 0 1.47 1.47 0 0 1 .38.11.38.38 0 0 1 .19.16.43.43 0 0 1 .05.23.48.48 0 0 1-.07.21.346.346 0 0 1-.4.13.92.92 0 0 0-.39-.09.61.61 0 0 0-.47.18.77.77 0 0 0-.16.52.75.75 0 0 0 .16.52.58.58 0 0 0 .47.19h.19l.2-.07a.27.27 0 0 1 .23 0 .26.26 0 0 1 .16.13.38.38 0 0 1 .07.21.37.37 0 0 1-.05.23.38.38 0 0 1-.19.16 2.15 2.15 0 0 1-.37.11Z' fill='%23e8f5e9'/%3E%3Cpath id='Path_4' data-name='Path 4' d='M11.79 18.11a2.92 2.92 0 0 1-.51 0 1.62 1.62 0 0 1-.47-.13.38.38 0 0 1-.24-.2.39.39 0 0 1 0-.26.35.35 0 0 1 .16-.2.3.3 0 0 1 .28 0 1.93 1.93 0 0 0 .42.12 1.69 1.69 0 0 0 .38 0 .56.56 0 0 0 .3-.06.18.18 0 0 0 .09-.16.15.15 0 0 0-.06-.13A.38.38 0 0 0 12 17l-.63-.11a.91.91 0 0 1-.55-.25.74.74 0 0 1-.2-.53.85.85 0 0 1 .17-.51 1.07 1.07 0 0 1 .46-.33 1.83 1.83 0 0 1 .68-.12 3.17 3.17 0 0 1 .47 0 2 2 0 0 1 .41.13.33.33 0 0 1 .2.2.39.39 0 0 1 0 .26.35.35 0 0 1-.17.19.3.3 0 0 1-.28 0 2.19 2.19 0 0 0-.34-.11 1.36 1.36 0 0 0-.27 0 .62.62 0 0 0-.33.07.18.18 0 0 0-.1.16c0 .1.07.16.21.19l.64.11a1 1 0 0 1 .56.24.7.7 0 0 1 .2.52.83.83 0 0 1-.36.72 1.56 1.56 0 0 1-.98.28Z' fill='%23e8f5e9'/%3E%3Cpath id='Path_5' data-name='Path 5' d='M14.84 18.1a.59.59 0 0 1-.57-.4l-.82-1.87a.51.51 0 0 1 0-.45.46.46 0 0 1 .44-.2.44.44 0 0 1 .29.09.63.63 0 0 1 .21.32l.48 1.24.51-1.25a.72.72 0 0 1 .2-.31.53.53 0 0 1 .32-.09.4.4 0 0 1 .27.09.36.36 0 0 1 .13.24.53.53 0 0 1-.05.32l-.84 1.88a.58.58 0 0 1-.57.39Z' fill='%23e8f5e9'/%3E%3C/g%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: cover;
    width: 23px;
    height: 30px;
    margin-left: 7px;
    cursor: pointer;
    &:hover {
      height: 33px;
      width: 25px;
    }
  }
  .excel {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='20' viewBox='0 0 15 20'%3E%3Cg id='_7267712_ext_xls_excel_spreadsheet_microsoft_icon' data-name='7267712_ext_xls_excel_spreadsheet_microsoft_icon' transform='translate(-4.5 -2)'%3E%3Cpath id='Path_11' data-name='Path 11' d='M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19A3 3 0 0 1 16.5 22Z' fill='%234caf50'/%3E%3Cpath id='Path_12' data-name='Path 12' d='M18.8 7.74H15.2a1.5 1.5 0 0 1-1.5-1.5V2.64a.55.55 0 0 1 .94-.39L19.19 6.8a.55.55 0 0 1-.39.94Z' fill='%23b9f6ca'/%3E%3Cpath id='Path_13' data-name='Path 13' d='M8.44 18.1a.45.45 0 0 1-.31-.1A.33.33 0 0 1 8 17.73a.49.49 0 0 1 .13-.32l.66-.81-.59-.72a.47.47 0 0 1-.13-.33.35.35 0 0 1 .11-.26.45.45 0 0 1 .31-.11.66.66 0 0 1 .3.07.77.77 0 0 1 .23.19l.35.46.36-.46a.64.64 0 0 1 .22-.19.66.66 0 0 1 .3-.07.45.45 0 0 1 .31.11.35.35 0 0 1 .11.26.51.51 0 0 1-.13.33l-.6.72.67.81a.44.44 0 0 1 .13.32.31.31 0 0 1-.12.26.45.45 0 0 1-.31.11A.65.65 0 0 1 10 18a.7.7 0 0 1-.23-.2l-.43-.54L9 17.84a.75.75 0 0 1-.22.19A.56.56 0 0 1 8.44 18.1Z' fill='%23e8f5e9'/%3E%3Cpath id='Path_14' data-name='Path 14' d='M12.53 18.11a1 1 0 0 1-.81-.29 1.33 1.33 0 0 1-.26-.89V14.48A.45.45 0 0 1 12 14a.52.52 0 0 1 .38.13.5.5 0 0 1 .13.38V16.9a.52.52 0 0 0 .08.31.29.29 0 0 0 .22.09H13a.15.15 0 0 1 .14.07.66.66 0 0 1 .05.31.51.51 0 0 1-.08.3.43.43 0 0 1-.25.13h-.33Z' fill='%23e8f5e9'/%3E%3Cpath id='Path_15' data-name='Path 15' d='M14.69 18.11a3.12 3.12 0 0 1-.52 0 1.64 1.64 0 0 1-.46-.13.38.38 0 0 1-.24-.2.39.39 0 0 1 0-.26.35.35 0 0 1 .16-.2.28.28 0 0 1 .27 0 2.18 2.18 0 0 0 .43.12 1.69 1.69 0 0 0 .38 0 .56.56 0 0 0 .3-.06.18.18 0 0 0 .09-.16.15.15 0 0 0-.06-.13.38.38 0 0 0-.17-.06l-.63-.11a.91.91 0 0 1-.55-.25.74.74 0 0 1-.2-.53.85.85 0 0 1 .17-.51 1.07 1.07 0 0 1 .46-.33 1.83 1.83 0 0 1 .68-.12 3.3 3.3 0 0 1 .47 0 2 2 0 0 1 .41.13.33.33 0 0 1 .2.2.39.39 0 0 1 0 .26.35.35 0 0 1-.17.19.3.3 0 0 1-.28 0 2.19 2.19 0 0 0-.34-.11 1.47 1.47 0 0 0-.27 0 .62.62 0 0 0-.33.07.18.18 0 0 0-.1.16c0 .1.07.16.21.19l.64.11a1 1 0 0 1 .56.24.7.7 0 0 1 .2.52.83.83 0 0 1-.36.72 1.56 1.56 0 0 1-.95.25Z' fill='%23e8f5e9'/%3E%3C/g%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: cover;
    width: 23px;
    height: 30px;
    margin-left: 7px;
    cursor: pointer;
    &:hover {
      height: 33px;
      width: 25px;
    }
  }
}
/** --------- End pdf export icon and guid and .... */
/** refresh div */
.refresh-div {
  width: 30px;
  height: 30px;
  cursor: pointer;
  .refresh {
    position: absolute;
    left: 50px;
    top: 100px;
    width: inherit;
    height: inherit;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 58 58.139'%3E%3Cg id='Group_534' data-name='Group 534' transform='translate(6765 -1401.861)'%3E%3Ccircle id='Ellipse_419' data-name='Ellipse 419' cx='29' cy='29' r='29' transform='translate(-6765 1402)' fill='%2381d2ff'/%3E%3Cpath id='Path_10819' data-name='Path 10819' d='M388.925-5.385s28.938.234 28.8 28.846-28.789 28.874-28.789 28.874V24.67Z' transform='translate(-7125.074 1407.245)' fill='%2309b7fc'/%3E%3Cpath id='Path_10818' data-name='Path 10818' d='M33.238 18.165a1.159 1.159 0 0 1-2.319 0ZM0 18.165a1.159 1.159 0 1 1 2.319 0ZM27.487 27.46a1.16 1.16 0 1 1 1.762 1.509ZM5.751 8.871A1.16 1.16 0 1 1 3.989 7.362ZM2.072 1.155A1.17 1.17 0 0 1 3.247 0 1.147 1.147 0 0 1 4.39 1.164ZM3.231 4.23l-1.16-.006h0ZM8.92 9.919v0Zm3.061-1.173a1.159 1.159 0 1 1 0 2.319ZM3.849 8.739l-.912.71h0Zm.557.547-.711.917h0ZM31.291 35.052a1.159 1.159 0 1 1-2.319-.011Zm-1.144-3.075 1.16 0Zm-5.689-5.689-.015-1.159h0Zm-3.076 1.173a1.159 1.159 0 0 1 0-2.319Zm8.132.006.912-.71h0Zm-.557-.549L29.667 26h0ZM16.619 1.546A16.616 16.616 0 0 1 33.238 18.165H30.919a14.3 14.3 0 0 0-14.3-14.3Zm0 33.238A16.616 16.616 0 0 1 0 18.165H2.319a14.3 14.3 0 0 0 14.3 14.3Zm12.63-5.816a16.587 16.587 0 0 1-12.63 5.816V32.465A14.279 14.279 0 0 0 27.487 27.46ZM3.989 7.362a16.588 16.588 0 0 1 12.63-5.816V3.865A14.279 14.279 0 0 0 5.751 8.871Zm.4-6.2v3.07L2.072 4.224V1.155ZM8.9 8.759l3.077-.014v2.319l-3.061.014ZM4.39 4.234a22.752 22.752 0 0 0 .062 2.729A1.975 1.975 0 0 0 4.777 8.03L2.937 9.449a4.283 4.283 0 0 1-.8-2.236 29.381 29.381 0 0 1-.062-2.99Zm4.53 6.844a29.33 29.33 0 0 1-3-.076 4.215 4.215 0 0 1-2.226-.8L5.117 8.37A1.965 1.965 0 0 0 6.184 8.7 27.191 27.191 0 0 0 8.9 8.759ZM4.777 8.03a1.468 1.468 0 0 0 .34.34L3.695 10.2a4.268 4.268 0 0 1-.758-.754ZM28.972 35.041l.015-3.069 2.319.009-.015 3.07Zm-4.514-7.594-3.076.014V25.142l3.061-.014Zm4.53 4.525c0-1.252 0-2.093-.062-2.729a2.115 2.115 0 0 0-.325-1.067l1.824-1.419a4.274 4.274 0 0 1 .8 2.234c.077.8.077 1.793.077 2.99Zm-4.545-6.844a28.476 28.476 0 0 1 3 .076 4.21 4.21 0 0 1 2.226.8l-1.422 1.835a1.939 1.939 0 0 0-1.066-.328 26.711 26.711 0 0 0-2.721-.062ZM28.6 28.177a1.854 1.854 0 0 0-.355-.34L29.667 26a3.865 3.865 0 0 1 .758.756Z' transform='matrix(0.259  -0.966  0.966  0.259  -6757.937  1442.097)' fill='%23fff'/%3E%3C/g%3E%3C/svg%3E");
  }
  &:hover {
    .refresh {
      transform: rotate(20deg);
    }
  }
}

.loader-wait-request {
  width: 40px;
  height: 40px;
  border-radius: 50px;
  border-top: 3px solid dodgerblue;
  border-right: 3px solid dodgerblue;
  border-left: 3px solid dodgerblue;
  border-bottom: 3px solid #ededed;
  animation: loader 1s linear infinite;

  @keyframes loader {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
}
table {
  border-collapse: collapse !important;
  width: 100%;
  th,
  td {
    white-space: nowrap;
    text-align: center;
  }
  thead {
    background-color: #dfdfdf;
    th {
      padding: 10px;
    }
  }
  tbody {
    tr:nth-child(odd) {
      background-color: #f6f6f6;
    }
    tr:nth-child(even) {
      background-color: #ededed;
    }
    td {
      padding: 10px;
    }
  }
}

input :not([type='checkbox']) {
  border: rgba(203, 209, 218, 0.5) 1px solid;
  padding: 7px 10px;
  background: white;
  font-size: 14px;
  outline: none;
  color: gray;
  user-select: auto;
  transition: none !important;
  /* Standard syntax */
  -webkit-appearance: none !important;
  -webkit-user-select: auto;
  /* Chrome, Opera, Safari */
  -moz-user-select: auto;
  /* Firefox 2+ */
  -ms-user-select: auto;

  /* IE 10+ */
  &:not(:disabled) {
    direction: rtl;
  }

  &:focus {
    outline: none;
  }
}
.export {
  span {
    cursor: pointer;
    padding: 20px;
    &:hover {
      font-weight: bolder;
    }
  }
}
// ** navigation for table */
.navigation {
  width: 100% !important;
  padding: 1% 0;
  display: flex;
  align-items: center;
  justify-content: flex-start;

  .back,
  .next {
    margin: 0 5px;
    border: none;
    background-color: var(--sidebar);
    padding: 8px 8px;
    border-radius: 5px;
    color: var(--black);
    font-weight: bold;
    cursor: pointer;
    white-space: nowrap;
    &:disabled {
      cursor: no-drop;
      opacity: 0.4;
    }
  }
}

// btn-warning
.btn-submit {
  border-radius: 9px;
  background-color: #ffb115;
  padding: 8px 20px;
  color: #000;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-direction: row-reverse;
  border: 1px solid #ffb115;
  white-space: nowrap;
  min-width: 120px;

  &:hover {
    cursor: pointer;
    color: #eea512;
    background-color: #fff8eb;

    svg {
      stroke: #eea512;
      transition: all 0.1s linear !important;
    }
  }

  &:disabled {
    pointer-events: none;
    opacity: 0.6;
  }

  @media (max-width: 1400px) {
    font-size: 12px;
    padding: 6px 16px;
  }
}

/** vue good table */
.vgt-table {
  margin-top: 20px;
  th,
  td {
    font-size: 13px;
    text-align: center;
  }
}
.vgt-global-search {
  margin: 5px 10px 0 0;
  max-width: 400px;
  padding: 5px;
}
.vgt-wrap__footer {
  * {
    font-size: 13px !important;
  }
  .footer__row-count__label {
    margin: 0;
  }
}
/** ---------------- End css for all component -----------------------*/
</style>