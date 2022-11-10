<template>
    <b-container>
      <b-row class="mt-4">
        <b-col cols="6" class="mx-auto">
          <b-card title="Private Dashboard">
            Welcome <b>{{ $auth.user.name }}</b>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </template>
  
  <script>
  export default {
    middleware : ['auth']
  }
  </script>
  