<template>
  <div>
    <div class="container-fluid pt-4">
      <div class="row my-3">
        <div class="col-md-8 offset-4 d-flex justify-content-between">
          <div class="col-3">
            <div class=""  v-if="isEditMode">
              <button class="btn btn-primary" @click="create">
                Create
              </button>
            </div>
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
                  <button @click.prevent="view" :disabled="isDisable" class="btn btn-primary" type="button">
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
                    :src="`${$axios.defaults.baseURL}` + `${photo}`"
                    accept="image/png, image/jpeg, image/jpg, image/jfif"
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
          <table class="table">
            <thead class="thead-light">
              <tr class="my-4">
                <th scope="col" class="py-3">#</th>
                <th scope="col">Post Title</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody v-if="postsLength == 0">
              <tr>
                <td colspan="4" class="text-center">There is no record</td>
              </tr>
            </tbody>
            <tbody v-else v-for="post in posts.data" :key="post.id">
              <tr>
                <td>{{ post.id }}</td>
                <td>{{ post.title }}</td>
                <td>
                  <img
                    width="60px"
                    height="60px"
                    class="img-fluid rounded border"
                    :src="`${$axios.defaults.baseURL}` + post.image"
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

      <div class="row"  v-if="this.count > 0">
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

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});


export default {
  middleware: ["auth"],
  data() {
    return {
      isEditMode: false,
      posts: {},
      photo: "",
      search: "",
      count : 1,
      totalPages: 1,
      postsLength:1,
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
      this.$axios
        .get(`api/post?page=${page}&search=${this.search}`)
        .then((response) => {
          this.posts = response.data;
          this.postsLength = this.posts.data.length;
          this.totalPages = response.data.last_page;
          this.count = response.data.total
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
      const data = new FormData();
      data.append('title',this.post.title)
      this.sizeCheck(this.post.image)
      typeof this.post.image == 'object' ? data.append('image',this.post.image,this.post.image.name) : this.post.image = ''
      this.$axios
        .post(`api/post`,data)
        .then((response) => {
          this.view();
          Toast.fire({
              icon: 'success',
              title: 'Created successfully!'
          });
          this.post.id = "";
          this.post.title = "";
          this.$refs.file.value = null;
          this.post.image = "";
          this.error.title = "";
          this.error.image = "";
        })
        .catch((error) => {
          error.response.data.errors.title ? this.error.title = error.response.data.errors.title[0] : this.error.title= '';
          error.response.data.errors.image ? this.error.image = error.response.data.errors.image[0] : this.error.image ="";
        });
    },
    edit(post) {
      this.isEditMode = true;
      this.post.id = post.id;
      this.post.title = post.title;
      this.post.image = post.image;
      this.$refs.file.value = null;
      this.photo = post.image;
      this.error.title = "";
      this.error.image = "";
    },
    sizeCheck(image){
      if(image.size > 1024 * 1024){
        this.post.title == '' ? this.error.title = "Title field is required!" : this.error.title = ''
        this.error.image = "Image file size is too large";
      }else{
        this.error.image = ''; 
      }
    },
    update() {
      const data = new FormData();
      data.append('title',this.post.title)
      this.sizeCheck(this.post.image)
      typeof this.post.image == 'object' ? data.append('image',this.post.image,this.post.image.name) : this.post.image = ''
      this.$axios
        .post(`api/post/${this.post.id}`,data)
        .then((response) => {
          this.view();
          Toast.fire({
              icon: 'success',
              title: 'Updated successfully!'
            });
          this.isEditMode = false;
          this.post.id = "";
          this.post.title = "";
          this.post.image = "";
          this.photo = "";
          this.error.title = "";
          this.error.image = "";
          this.$refs.file.value = null;
        })
        .catch((error) => {
          error.response.data.errors.title ? this.error.title = error.response.data.errors.title[0] : this.error.title= '';
          error.response.data.errors.image ? this.error.image = error.response.data.errors.image[0] : this.error.image ="";
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
          this.$axios.delete(`api/post/${id}`).then((response) => {
            this.view();
            Toast.fire({
              icon: 'success',
              title: 'Deleted successfully!'
            });
          });
        }
      });
    },
  },
  computed: {
    isDisable() {
      return this.search.length <= 0;
    },
  },
  created() {
    this.view(1);
  },
};
</script>
