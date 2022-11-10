<template>
  <div>
    <b-container class="mt-4">
      <b-row>
        <b-col cols="6">
          <b-card>
            <b-card-header>
              <h1>Register</h1>
            </b-card-header>
            <b-card-body>
              <b-form @submit.prevent="register" v-if="show">
                <b-form-group id="input-group-2" label="Name:" label-for="input-1">
                  <b-form-input
                    id="input-1"
                    v-model="form.name"
                    placeholder="Enter name"
                  ></b-form-input>
                  <small
                    v-if="this.error.name"
                    class="text-danger font-weight-bolder"
                    v-html="this.error.name"
                  />
                </b-form-group>

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

                <b-form-group id="input-group-1" label="Password:" label-for="input-3">
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

                <b-button type="submit" variant="primary">Register</b-button>
                <b-button to="/login">Login</b-button>
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
        name: this.name,
        email: this.email,
        password: this.password,
      },
      error: { name: "", email: "", password: "" },
      show: true,
    };
  },
  methods: {
    async register() {
      try {
        await this.$axios.post("http://localhost:8000/api/register", this.form);
        this.$router.push("/login");
      } catch (error) {
        console.log(error);
        this.error.name = error.response.data.errors.name[0];
        this.error.email = error.response.data.errors.email[0];
        this.error.password = error.response.data.errors.password[0];
      }
    },
  },
};
</script>
