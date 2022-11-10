<template>
  <div>
    <div class="container-fluid pt-4">
      <div class="row my-3">
        <div class="col-md-8 offset-4 d-flex justify-content-between">
          <div class="col-3">
            <button class="btn btn-primary" @click="create">Create</button>
          </div>
          <div class="col-5">
            <form action="">
              <div class="input-group mb-3">
                <input
                  type="text"
                  v-model="search"
                  name="search"
                  class="form-control"
                  placeholder="Search..."
                  aria-label="Search..."
                  aria-describedby="basic-addon2"
                />
                <div class="input-group-append">
                  <button @click.prevent="view" class="btn btn-primary" type="button">
                    Search
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h4>{{ isEditMode ? "Edit" : "Create" }}</h4>
            </div>
            <div class="card-body">
              <form method="post" id="form" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="exampleFormControlInput1">Title</label>
                  <input
                    type="text"
                    v-model="post.title"
                    class="form-control"
                    name="title"
                    id="file1"
                    placeholder="Enter Post Title..."
                  />
                  <small
                    v-if="this.error.title"
                    class="text-danger font-weight-bolder"
                    v-html="this.error.title"
                  />
                </div>
                <div v-if="isEditMode">
                  <img
                    width="80px"
                    height="80px"
                    :src="'http://localhost:8000/' + `${photo}`"
                    class="img-fluid rounded border"
                    alt=""
                  />
                </div>
                <div class="form-group">
                  <label for="exampleFormControlInput1">Image</label>
                  <input
                    type="file"
                    ref="file"
                    id="file1"
                    name="image"
                    @change="onFileSelected"
                    class="form-control pt-1"
                  />
                  <small
                    v-if="this.error.image"
                    class="text-danger font-weight-bolder"
                    v-html="this.error.image"
                  />
                </div>
                <button
                  class="btn btn-primary"
                  @click.prevent="isEditMode ? update() : store()"
                >
                  {{ isEditMode ? "Update" : "Add" }}
                </button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Post Title</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody v-for="post in posts.data" :key="post.id">
              <tr>
                <td>{{ post.id }}</td>
                <td>{{ post.title }}</td>
                <td>
                  <img
                    width="60px"
                    height="60px"
                    class="img-fluid rounded border"
                    :src="'http://localhost:8000/' + post.image"
                    alt=""
                  />
                </td>
                <td>
                  <button class="btn btn-sm btn-success" @click.prevent="edit(post)">
                    Edit
                  </button>
                  <button class="btn btn-sm btn-danger" @click.prevent="destroy(post.id)">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 offset-4">
          <vs-pagination :total-pages="totalPages" @change="view"></vs-pagination>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Form from "vform";
import Swal from "sweetalert2";
import VsPagination from "@vuesimple/vs-pagination";

export default {
  middleware: ["auth"],
  data() {
    return {
      isEditMode: false,
      posts: {},
      photo: "",
      search: "",
      totalPages: "",
      error: {
        title: "",
        image: "",
      },
      post: new Form({
        id: "",
        title: "",
        image: "",
      }),
    };
  },
  methods: {
    async view(page = 1) {
      axios
        .get(`http://127.0.0.1:8000/api/post?page=${page}&search=${this.search}`)
        .then((response) => {
          this.posts = response.data;
          this.totalPages = response.data.last_page;
          console.log(response.data.last_page);
        });
    },
    onFileSelected(event) {
      this.post.image = event.target.files[0];
    },

    create() {
      this.isEditMode = false;
      this.post.id = "";
      this.post.title = "";
      this.$refs.file.value = null;
      this.post.image = "";
    },
    store() {
      this.post
        .post("http://127.0.0.1:8000/api/post")
        .then((response) => {
          this.view();
          this.post.id = "";
          this.post.title = "";
          this.$refs.file.value = null;
          this.post.image = "";
        })
        .catch((error) => {
          this.error.title = error.response.data.errors.title[0];
          this.error.image = error.response.data.errors.image[0];
          console.log(error);
        });
    },
    edit(post) {
      this.isEditMode = true;
      this.post.id = post.id;
      this.post.title = post.title;
      this.post.image = post.image;
      this.photo = post.image;
      this.$refs.file = post.image;
      this.error.title = "";
      this.error.image = "";
    },
    update() {
      this.post
        .post(`http://127.0.0.1:8000/api/post/${this.post.id}`)
        .then((response) => {
          this.view();
          this.isEditMode = false;
          this.post.id = "";
          this.post.title = "";
          this.post.image = "";
          this.photo = "";
          this.$refs.file.value = null;
        })
        .catch((error) => {
          this.error.title = error.response.data.errors.title[0];
          this.error.image = error.response.data.errors.image[0];
          console.log(error);
        });
    },
    destroy(id) {
      Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`http://127.0.0.1:8000/api/post/${id}`).then((response) => {
            this.view();
            Swal.fire({
              title: "Deleted!",
              icon: "success",
            });
          });
        }
      });
    },
  },
  created() {
    this.view(1);
  },
};
</script>
