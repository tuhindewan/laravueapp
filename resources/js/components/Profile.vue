<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background: url('/img/photo1.png') center center;">
                        <h3 class="widget-user-username text-right">Elizabeth Pierce</h3>
                        <h5 class="widget-user-desc text-right">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">SALES</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                <h5 class="description-header">13,000</h5>
                                <span class="description-text">FOLLOWERS</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                    <h3>Display User Activity</h3>
                  </div>

                  <div class="tab-pane active" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="name"
                                v-model="form.name"
                                name="name"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.has('name')}"
                                placeholder="Name">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email"
                                v-model="form.email"
                                name="email"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.has('email') }"
                                laceholder="Email">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                        <div class="col-sm-10">
                          <textarea class="form-control"
                                    name="bio"
                                    v-model="form.bio"
                                    placeholder="Experience"
                                    :class="{ 'is-invalid': form.errors.has('bio') }">
                            </textarea>
                            <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="photo" class="col-sm-2">Profile Photo</label>
                        <div class="col-sm-10">
                          <input type="file" @change="convertToBase64" name="photo" class="form-control">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="text"
                                v-model="form.password"
                                class="form-control"
                                name="password"
                                :class="{ 'is-invalid': form.errors.has('password') }"
                                placeholder="Password">
                            <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" @click.prevent="updateProfile" class="btn btn-danger">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                }),
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            axios.get("api/profile")
            .then(({ data }) => {this.form.fill(data)})

            Fire.$on('AfterCreate', () => {
                axios.get("api/profile")
                .then(({ data }) => {this.form.fill(data)})
            })
        },
        methods:{
            getProfilePhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo
                return photo
            },
          convertToBase64(e){
            let file = e.target.files[0]
            let reader  = new FileReader()
            if (file['size'] < 209715) {
                reader.onloadend = (file) => {
                    console.log(reader.result)
                    this.form.photo = reader.result
                }
                reader.readAsDataURL(file);
            }else{
                Toast.fire({
                        icon: 'error',
                        title: 'More than 2MB file is not supported'
                    })
            }

          },
          updateProfile(){
              this.$Progress.start()
              this.form.put('api/profile')
              .then(() => {
                  Fire.$emit('AfterCreate')
                  Toast.fire({
                        icon: 'success',
                        title: 'Information updated successfully'
                    })
                  this.$Progress.finish()
              })
              .catch(() => {
                  this.$Progress.fail()
              })
          }
        }
    }
</script>

<style scoped>
.card-footer{
    padding-top: 5px;
}
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}
</style>
