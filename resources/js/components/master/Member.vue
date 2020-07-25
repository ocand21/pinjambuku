<template>
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Member
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Member</h3>

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
                    <v-client-table :data="dataMember" :columns="columns" :options="options">
                        <div slot="aksi" slot-scope="{row}" class="text-center">
                            <a href="" style="margin: 5px;" @click.prevent="editModal(row)" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a>
                            <a href="" style="margin: 5px;" @click.prevent="hapusMember(row.id)" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus"><span class="fa fa-trash"></span></a>
                        </div>
                        <p slot="dob" slot-scope="{row}">{{row.dob | myDate}}</p>
                        <div slot="is_active" slot-scope="{row}" class="text-center">
                            <p class="badge bg-green" v-if="row.is_active == 1">Aktif</p>
                            <p class="badge bg-red" v-if="row.is_active == 0">Tidak Aktif</p>
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
                        <h4 class="modal-title" v-show="editMode == false">Tambah Member</h4>
                        <h4 class="modal-title" v-show="editMode == true">Ubah Member</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Nama Lengkap</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="fullname" v-model="form.fullname" :class="{'is-invalid' : form.errors.has('form.fullname')}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Lahir</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <date-picker name="dob" v-model="form.dob" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('dob') }"></date-picker>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Alamat</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="address" v-model="form.address" :class="{'is-invalid' : form.errors.has('form.address')}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group" v-show="editMode">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Status</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <v-select :options="[
                                    {value: '0', text: 'Tidak Aktif'}, {value: '1', text: 'Aktif'}
                                    ]" v-model="is_active" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                        <a href="#" @click.prevent="createMember()" v-show="editMode == false" class="btn btn-primary">Simpan</a>
                        <a href="#" @click.prevent="updateMember()" v-show="editMode == true" class="btn btn-primary">Simpan</a>
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
import VSelect from '@alfsnd/vue-bootstrap-select'
import DatePicker from 'vue2-datepicker'
export default {
    components: {
        VSelect,
        DatePicker,
    },
    data() {
        return {
            editMode: false,
            validationErrors: '',
            columns: [
                'aksi', 'fullname', 'dob', 'address', 'is_active'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|1 data",
                },
                headings: {
                    fullname: 'Nama Lengkap',
                    dob: 'Tanggal Lahir',
                    address: 'Alamat',
                    is_active: 'Status',
                },
                sortable: ['fullname', 'dob', 'address',],
                filterable: ['fullname', 'dob', 'address',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
            },
            form: new Form({
                id: '',
                fullname: '',
                dob: '',
                address: '',
                is_active: '',
            }),
            is_active: '',
            lang: {
                days: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                placeholder: {
                    date: 'Pilih Tanggal',
                }
            },
            optionsDate: {
                format: 'YYYY-mm-dd'
            },
            dataMember: [],
        }
    },
    methods: {
        hapusMember(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus member!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    axios.delete('/api/master-data/member/' + id)
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
        updateMember() {
            this.form.is_active = this.is_active.value
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.put('/api/master-data/member/' + this.form.id)
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
        editModal(member) {
            this.editMode = true;
            $('#modal-default').modal('show');
            this.form.id = member.id
            this.form.fullname = member.fullname
            this.form.dob = member.dob
            this.form.address = member.address
            if (member.is_active == '1') {
                this.is_active = {value: '1', text: 'Aktif'}
            } else {
                this.is_active = {value: '0', text: 'Tidak Aktif'}
            }
        },
        createMember() {
            this.$Progress.start();
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.post('/api/master-data/member')
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
            axios.get('/api/master-data/member').then(({
                data
            }) => (this.dataMember = data));
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
