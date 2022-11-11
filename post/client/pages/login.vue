<template>
  <div>
    <b-container class="mt-4">
      <b-row>
        <b-col cols="6">
          <b-card>
            <b-card-header>
              <h3>Login</h3>
            </b-card-header>
            <b-card-body>
              <small
                v-if="this.error.message"
                class="text-danger font-weight-bolder"
                v-html="this.error.message"
              />
              <b-form @submit.prevent="login" v-if="show">
                <b-form-group
                  id="input-group-1"
                  label="Email address:"
                  label-for="input-2"
                >
                  <b-form-input
                    id="input-2"
                    v-model="form.email"
                    type="email"
                    placeholder="Enter email"
                  ></b-form-input>
                  <small
                    v-if="this.error.email"
                    class="text-danger font-weight-bolder"
                    v-html="this.error.email"
                  />
                </b-form-group>

                <b-form-group
                  id="input-group-1"
                  label="Email address:"
                  label-for="input-3"
                >
                  <b-form-input
                    id="input-3"
                    v-model="form.password"
                    type="password"
                    placeholder="Enter Password"
                  ></b-form-input>
                  <small
                    v-if="this.error.password"
                    class="text-danger font-weight-bolder"
                    v-html="this.error.password"
                  />
                </b-form-group>

                <b-button type="submit" variant="primary">Login</b-button>
              </b-form>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  middleware: ["guest"],
  data() {
    return {
      form: {
        email: this.email,
        password: this.password,
      },
      error: {
        email: "",
        password: "",
        message: "",
      },
      show: true,
    };
  },
  methods: {
    login() {
      this.$auth
        .loginWith("laravelSanctum", {
          data: this.form,
        })
        .then((response) => this.$router.push("/post"))
        .catch((error) => {
          if (error.response.status == 401) {
            this.error.message = "Email or Password don't match";
            this.error.email = '';
            this.error.password = '';
          }
          if(error.response.status == 422){
            this.error.message = "";
            error.response.data.errors.email ? this.error.email = error.response.data.errors.email[0] : this.error.email='';
            error.response.data.errors.password ? this.error.password = error.response.data.errors.password[0] : this.error.password='';
          }
        });
    },
  },
  
};
</script>
