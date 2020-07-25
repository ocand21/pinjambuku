<template>
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">User</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                        <i class="fa fa-plus"></i> Tambah
                    </button>

                    <hr>
                    <v-client-table :data="dataUser" :columns="columns" :options="options">
                        <div slot="aksi" slot-scope="{row}" class="text-center">
                            <a href="" style="margin: 5px;" @click.prevent="editModal(row)" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a>
                            <a href="" style="margin: 5px;" @click.prevent="hapusUser(row.id)" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus"><span class="fa fa-trash"></span></a>
                        </div>
                        <div slot="status" slot-scope="{row}" class="text-center">
                            <p class="badge bg-green" v-if="row.status == 1">Aktif</p>
                            <p class="badge bg-red" v-if="row.status == 0">Tidak Aktif</p>
                        </div>
                    </v-client-table>
                </div>
            </div>
            <!-- /.box-body -->
            
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" v-show="editMode == false">Tambah User</h4>
                        <h4 class="modal-title" v-show="editMode == true">Ubah User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Nama Lengkap</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="name" v-model="form.name" :class="{'is-invalid' : form.errors.has('form.name')}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Email</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="email" v-model="form.email" :class="{'is-invalid' : form.errors.has('form.email')}" class="form-control" type="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Username</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="username" v-model="form.username" :class="{'is-invalid' : form.errors.has('form.username')}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group" v-show="!editMode">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Password</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="password" v-model="form.password" :class="{'is-invalid' : form.errors.has('form.password')}" class="form-control" type="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                        <a href="#" @click.prevent="createUser()" v-show="editMode == false" class="btn btn-primary">Simpan</a>
                        <a href="#" @click.prevent="updateUser()" v-show="editMode == true" class="btn btn-primary">Simpan</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>
</div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            validationErrors: '',
            columns: [
                'aksi', 'name', 'email', 'username',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|1 data",
                },
                headings: {
                    name: 'Nama',
                    email: 'Email',
                    username: 'Username',
                },
                sortable: ['name', 'email', 'username'],
                filterable: ['name', 'email', 'username'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
            },
            form: new Form({
                id: '',
                name: '',
                email: '',
                username: '',
                password: '',
            }),
            dataUser: [],
        }
    },
    methods: {
        hapusUser(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus user!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    axios.delete('/api/master-data/user/' + id)
                        .then(res => {
                            swal(
                                'Dihapus!',
                                res.data.msg,
                                'success'
                            )
                            Fire.$emit('AfterCreate')
                        }).catch(() => {
                            swal("Gagal!", "Terjadi kesalahan.",
                                "warning");
                        });
                }

            })
        },
        updateUser() {
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.put('/api/master-data/user/' + this.form.id)
                        .then(res => {
                            Fire.$emit('AfterCreate');
                            swal(
                                'Sukses!',
                                res.data.msg,
                                'success',
                            )
                            $('#modal-default').modal('hide');
                            this.form.reset();
                            this.validationErrors = '';
                            this.$Progress.finish();
                        }).catch(error => {
                            if (error.response.status == 422) {
                                this.validationErrors = error.response.data.errors;
                                swal(
                                    'Gagal!',
                                    'Terjadi kesalahan',
                                    'warning'
                                )

                            } else {
                                swal(
                                    'Server Error!',
                                    'Terjadi kesalahan pada server',
                                    'error'
                                )
                            }
                            this.$Progress.fail()
                        })
                }
            })
        },
        editModal(user) {
            this.editMode = true;
            $('#modal-default').modal('show');
            this.form.fill(user);
        },
        createUser() {
            this.$Progress.start();
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.post('/api/master-data/user')
                        .then(res => {
                            Fire.$emit('AfterCreate');
                            swal(
                                'Sukses!',
                                res.data.msg,
                                'success',
                            )
                            this.form.reset();
                            $('#modal-default').modal('hide');
                            this.validationErrors = '';
                            this.$Progress.finish();
                        }).catch(error => {
                            if (error.response.status == 422) {
                                this.validationErrors = error.response.data.errors;
                                swal(
                                    'Gagal!',
                                    'Terjadi kesalahan',
                                    'warning'
                                )

                            } else {
                                swal(
                                    'Server Error!',
                                    'Terjadi kesalahan pada server',
                                    'error'
                                )
                            }
                            this.$Progress.fail()
                        })
                }
            })
        },
        loadData() {
            axios.get('/api/master-data/user').then(({
                data
            }) => (this.dataUser = data));
        }
    },
    created() {
        this.$Progress.start();
        this.loadData();
        Fire.$on('AfterCreate', () => {
            this.loadData('');
        });
        this.$Progress.finish();
    }
}
</script>
