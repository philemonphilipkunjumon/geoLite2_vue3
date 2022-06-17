<template>
    <div class="mx-auto max-w-5xl min-h-screen p-4 border-solid border-2 rounded-md">
      <br />
      <h1 class="content-center w-full text-center text-2xl">MAXMIND GeoLite2 City Database</h1>
       <h2 class="text-center text-lg w-full italic">Implemented by Philemon Philip Kunjumon</h2><br /><hr /> <br />
      <Form @submit="onSubmit" class="p-4">
        <h2>Enter the IP Address:</h2>
        <Field :rules="validateField" class="input input-bordered m-2 w-full border-slate-900" type="text" name="tempIpList" v-model="tempIpList" @keyup="addIP"/>
        <div @click="deleteIp(ipAddress)" v-for="ipAddress in ipAddresses" :key="ipAddress" class="inline-block mx-2 my-1 py-2 px-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
          {{ipAddress}}
        </div>
        <div class="italic text-sm"> * Enter IP Addresses in the above field seperated by comma.<br /> * To remove the IP Address from the list just click the pill below.<br /> * Invalid IPs gets removed from the list. </div>
        <ErrorMessage class="text-red-500 text-M italic font-semibold" name="tempIpList"/> <br/><br/>
        <button id="submit" name="submit" type="submit" class="border-slate-900 border-2 p-3 w-auto">Get Geo Locations</button>
      </Form>
      <br />
      <hr />
      <br />
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-10">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
               IP Address
              </th>
              <th scope="col" class="px-6 py-3">
                Country Code
              </th>
              <th scope="col" class="px-6 py-3">
                Postal Code
              </th>
              <th scope="col" class="px-6 py-3">
                City Name
              </th>
              <th scope="col" class="px-6 py-3">
                Time Zone
              </th>
              <th scope="col" class="px-6 py-3">
                Accuracy Radius
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="responseData.length===0" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
             <td colspan="6" class="text-center px-6 py-4" >No Records to display</td>
            </tr>
            <tr v-for="data in responseData" :key="data" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" >
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                {{data.ip}}
              </th>
              <td class="px-6 py-4">
                {{data.country_code}}
              </td>
              <td class="px-6 py-4">
                {{data.postal_code}}
              </td>
              <td class="px-6 py-4">
                {{data.city_name}}
              </td>
              <td class="px-6 py-4 text-right">
                {{data.time_zone}}
              </td>
              <td class="px-6 py-4 text-right">
                {{data.accuracy_radius}}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
</template>

<script>
import {ErrorMessage, Field, Form} from 'vee-validate';
import axios from 'axios';
import {isIP} from 'is-ip';
export default {
  name: 'App',
  components: {
    Form, Field, ErrorMessage
  },
  data() {
    return {
      tempIpList:"",
      ipAddresses:[],
      responseData:[],
      responseException:[],
    }
  },
  methods: {
    addIP(e) {
      if(e.key===',' && this.tempIpList) {
        let ipadd=this.tempIpList.slice(0, -1);
        if(isIP(ipadd)) {
          if(!this.ipAddresses.includes(ipadd)) {
            this.ipAddresses.push(ipadd);
          }
        }
        this.tempIpList='';
      }
    },
    deleteIp(ip){
      this.ipAddresses =this.ipAddresses.filter((item)=>{
        return ip!==item
      })
    },
    validateField() {
      if(this.ipAddresses.length===0) {
        return 'Please enter at-least one ip address'
      }
      return true;
    },
    onSubmit(value) {
      console.log(value);
      this.fetchGeoDetails();
    },
    fetchGeoDetails() {
      let postParams={};
      postParams['data'] =this.ipAddresses;
      axios.post('./ajax/maxmind.php',JSON.stringify(postParams))
          .then(response =>{
            console.log(response);
            this.responseData =response.data.data;
            this.responseException =response.data.exception;
          })
          .catch(function (error_response) {
            console.log(error_response);
          });
    },
  },
}
</script>

<style>

</style>
