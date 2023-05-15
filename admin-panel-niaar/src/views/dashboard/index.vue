<template>
  <div>
    <div v-if="userData.role == 'manager' || userData.role == 'super-admin'">
      <b-card v-if="isLoaded">
        <b-row>
          <b-col md="6">
            <h3>Monthly Profit / Month</h3>
            <vue-apex-charts
              height="350"
              type="line"
              :options="chartOptions"
              :series="profitSeries"
            />
          </b-col>

          <b-col md="6">
            <h3>Monthly Inquiries / Month</h3>
            <vue-apex-charts
              :options="chartOptions"
              :series="inquiriesSeries"
            />
          </b-col>

          <b-col md="6">
            <h3>Monthly Customers / Month</h3>
            <vue-apex-charts
              :options="chartOptions"
              :series="customersSeries"
            />
          </b-col>

          <b-col md="6">
            <h3>Monthly Orders / Month</h3>
            <vue-apex-charts :options="chartOptions" :series="ordersSeries" />
          </b-col>
        </b-row>
      </b-card>

      <div class="text-center" v-else>
        <b-card>
          <b-spinner />
        </b-card>
      </div>
    </div>

    <inqStatistics />

    <avgResponseTime v-if="userData.role!== 'client' " />

    <!-- <pendingTasks /> -->

    <statistics v-if="userData.role !== 'client'" />
    
  </div>
</template>

<script>
import { BRow, BCol, BSpinner, BCard } from "bootstrap-vue";
import inqStatistics from "./inqStatistics.vue";
import avgResponseTime from "./avgResponseTime.vue";
import pendingTasks from "./pendingTasks.vue";
import statistics from "./statistics.vue";
import VueApexCharts from "vue-apexcharts";

export default {
  components: {
    BRow,
    BCard,
    BCol,
    BSpinner,
    inqStatistics,
    avgResponseTime,
    VueApexCharts,
    pendingTasks,
    statistics,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      ordersSeries: [
        {
          name: "Order",
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        },
      ],
      customersSeries: [
        {
          name: "Customer",
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        },
      ],
      inquiriesSeries: [
        {
          name: "Inquiry",
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        },
      ],
      profitSeries: [
        {
          name: "Profit",
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        },
      ],
      chartOptions: {
        chart: {
          type: "line",
          dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
          },
          toolbar: {
            show: true,
            tools: {
              download: true,
              zoom: false,
              zoomin: false,
              zoomout: false,
              selection: false,
              reset: false,
              pan: false,
            },
          },
        },
        colors: ["#77B6EA", "#545454"],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: "smooth",
        },
        title: {
          text: "",
          align: "left",
        },
        grid: {
          borderColor: "#e7e7e7",
          row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
          },
        },
        markers: {
          size: 1,
        },
        xaxis: {
          categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          title: {
            text: "Month",
          },
        },
        yaxis: {
          title: {
            text: "Data",
          },
        },
        legend: {
          position: "top",
          horizontalAlign: "right",
          floating: true,
          offsetY: -25,
          offsetX: -5,
        },
      },
      data: {},
      isLoaded: false,
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    this.getData();
  },
  methods: {
    getData() {
      axios
        .get("/admin/dashboard/stats/charts")
        .then((response) => {
          // Profit
          try {
            for (
              let index = 0;
              index <= Object.keys(response.data.profit).length;
              index++
            ) {
              this.profitSeries[index].name =
                "Profit (" + Object.keys(response.data.profit)[index] + ")";
              this.profitSeries[index].data = Object.values(
                response.data.profit[Object.keys(response.data.profit)[index]]
              );
            }
          } catch (error) {
            // console.log(error);
          }

          // Orders
          try {
            for (
              let index = 0;
              index <= Object.keys(response.data.orders).length;
              index++
            ) {
              this.ordersSeries[index].name =
                "Order (" + Object.keys(response.data.orders)[index] + ")";
              this.ordersSeries[index].data = Object.values(
                response.data.orders[Object.keys(response.data.orders)[index]]
              );
            }
          } catch (error) {
            // console.log(error);
          }

          // Active customers
          try {
            for (
              let index = 0;
              index <= Object.keys(response.data.active_customers).length;
              index++
            ) {
              this.customersSeries[index].name =
                "Customer (" +
                Object.keys(response.data.active_customers)[index] +
                ")";
              this.customersSeries[index].data = Object.values(
                response.data.active_customers[
                  Object.keys(response.data.active_customers)[index]
                ]
              );
            }
          } catch (error) {
            // console.log(error);
          }

          // Inquiry
          try {
            for (
              let index = 0;
              index <= Object.keys(response.data.inquiry).length;
              index++
            ) {
              this.inquiriesSeries[index].name =
                "Inquiry  (" + Object.keys(response.data.inquiry)[index] + ")";
              this.inquiriesSeries[index].data = Object.values(
                response.data.inquiry[Object.keys(response.data.inquiry)[index]]
              );
            }
          } catch (error) {
            // console.log(error);
          }

          this.isLoaded = true;
        })
        .catch((error) => {
          this.isLoaded = true;
        });
    },
  },
};
import axios from "@axios";
</script>

<style lang="scss">
@import "@core/scss/vue/pages/dashboard-ecommerce.scss";
@import "@core/scss/vue/libs/chart-apex.scss";
</style>
