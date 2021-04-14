<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">
                        Add New
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Registered At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>{{ user.id }}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td><span class="tag tag-success">{{ user.type | upText }}</span></td>
                      <td>{{ user.created_at | myDate }}</td>
                      <td>
                          <a type="button" @click="editModal(user)">
                              <i class="fas fa-edit text-blue"></i>
                          </a>
                          /
                          <a type="button" @click="deleteUser(user.id)">
                              <i class="fas fa-trash text-red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults">
                        <span slot="prev-nav">&lt; Previous</span>
	                    <span slot="next-nav">Next &gt;</span>
                  </pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserLabel" v-show="!editmode">Add New</h5>
                        <h5 class="modal-title" id="addUserLabel" v-show="editmode">Update User'f Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                    placeholder="Enter Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                    placeholder="Enter Email Address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" id="bio" name="bio"
                                    placeholder="Short bio for user (Optional)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <select name="type" id="type" v-model="form.type"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Standard User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password"
                                    placeholder="Enter Password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" v-show="!editmode" class="btn btn-primary btn-sm">Create</button>
                            <button type="submit" v-show="editmode" class="btn btn-success btn-sm">Update</button>
                        </div>
                    </form>
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
                users: {},
                editmode: true
            }
        },
        methods:{
            getResults(page = 1) {
                axios.get('api/users?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
		    },
            createUser(){
                this.$Progress.start()
                this.form.post('api/users ')
                .then(()=>{
                    Fire.$emit('AfterCreate')
                    $('#addUser').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'User created successfully'
                        })
                    this.$Progress.finish()
                })
                .catch((error)=>{
                    console.log(error)
                })
            },
            getAllUsers(){
                axios.get('api/users').then( ( {data} ) => (this.users = data) )
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.form.delete('api/users/'+id).then(()=>{
                                Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                                Fire.$emit('AfterCreate')
                            }).catch((error)=>{
                                console.log(error)
                            })
                        }
                    })
            },
            newModal(){
                this.editmode = false
                this.form.reset()
                $('#addUser').modal('show')
            },
            editModal(user){
                this.editmode = true
                this.form.reset()
                $('#addUser').modal('show')
                this.form.fill(user)
            },
            updateUser(){
                this.$Progress.start()
                this.form.put('api/users/'+ this.form.id)
                .then(() =>{
                    Fire.$emit('AfterCreate')
                    $('#addUser').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'User info updated successfully'
                        })
                    this.$Progress.finish()
                })
                .catch(() =>{
                    this.$Progress.fail()
                })
            }
        },
        created() {
            this.getAllUsers()

            Fire.$on('AfterCreate', () => {
                this.getAllUsers()
            })

            Fire.$on('searching', () => {
                let query  = this.$parent.search
                axios.get('api/finduser?q=' + query)
                .then((data) => {
                    this.users = data.data
                })
                .catch()
            })
        }
    }
</script>
