<template>
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pinjam Buku
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Pinjam Buku</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">

                    <h5>List buku yang tersedia untuk dipinjam</h5>
                    <hr>
                    <v-client-table :data="dataBuku" :columns="columns" :options="options">
                        <div slot="aksi" slot-scope="{row}" class="text-center">
                            <a href="" style="margin: 5px;" @click.prevent="modalPinjam(row)" class="btn btn-success btn-sm" data-toggle="tooltip" title="Pinjam Buku"><span class="fa fa-check"></span></a>
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
                        <h4 class="modal-title">Pinjam Buku</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Judul Buku</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="title" v-model="form.title" :class="{'is-invalid' : form.errors.has('form.title')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Pengarang</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="author" v-model="form.author" :class="{'is-invalid' : form.errors.has('form.author')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">ISBN</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="isbn" v-model="form.isbn" :class="{'is-invalid' : form.errors.has('form.isbn')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tahun Terbit</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="published" v-model="form.published" :class="{'is-invalid' : form.errors.has('form.published')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Nama Peminjam</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <v-select :options="dataMember" v-model="id_member" :searchable="searchableMember" :labelSearchPlaceholder="plcMember" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Pinjam</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <date-picker name="borrow_date" v-model="form.borrow_date" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('borrow_date') }"></date-picker>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Pengembalian</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <date-picker name="return_date" v-model="form.return_date" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('return_date') }"></date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                        <a href="#" @click.prevent="borrowBook()" v-show="editMode == false" class="btn btn-primary">Simpan</a>
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
                'aksi', 'title', 'author', 'isbn', 'published'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|1 data",
                },
                headings: {
                    title: 'Judul',
                    author: 'Pengarang',
                    isbn: 'ISBN',
                    published: 'Diterbitkan',
                },
                sortable: ['title', 'author', 'isbn', 'published'],
                filterable: ['title', 'author', 'isbn', 'published'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
            },
            form: new Form({
                id: '',
                title: '',
                author: '',
                isbn: '',
                published: '',
                id_member: '',
                borrow_date: '',
                return_date: '',
            }),
            lang: {
                days: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                placeholder: {
                    date: 'Pilih Tanggal',
                }
            },
            dataBuku: [],
            id_member: '',
            dataMember: [],
            plcMember: '-Ketik Nama Member-',
            searchableMember: true,
        }
    },
    methods: {
        modalPinjam(book) {
            this.loadMember();
            $('#modal-default').modal('show');
            this.form.fill(book);
        },
        borrowBook() {
            this.form.id_member = this.id_member.value
            this.$Progress.start();
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.post('/api/transaksi/buku/pinjam/'+this.form.id)
                        .then(res => {
                            Fire.$emit('AfterCreate');
                            swal(
                                'Sukses!',
                                res.data.msg,
                                'success',
                            )
                            this.form.reset();
                            $('#modal-default').modal('hide');
                            this.validationErrors = ''
                            this.id_member = ''
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
        loadMember(){
            axios.get('/api/transaksi/member/aktif').then(({
                data
            }) => (this.dataMember = data));
        },
        loadData() {
            axios.get('/api/transaksi/buku/tersedia').then(({
                data
            }) => (this.dataBuku = data));
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
