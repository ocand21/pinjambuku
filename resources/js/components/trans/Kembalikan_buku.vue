<template>
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pengembalian Buku
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Pengembalian Buku</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">

                    <h5>List buku yang dipinjam</h5>
                    <hr>
                    <v-client-table :data="dataBuku" :columns="columns" :options="options">
                        <div slot="aksi" slot-scope="{row}" class="text-center">
                            <a href="" style="margin: 5px;" @click.prevent="editModal(row)" class="btn btn-success btn-sm" data-toggle="tooltip" title="Kembalikan Buku"><span class="fa fa-exchange-alt"></span></a>
                        </div>
                        <p slot="borrow_date" slot-scope="{row}">{{row.borrow_date | myDate}}</p>

                        <p slot="return_date" slot-scope="{row}">{{row.return_date | myDate}}</p>
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
                        <h4 class="modal-title">Pengembalian Buku</h4>
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
                                    <input name="fullname" v-model="form.fullname" :class="{'is-invalid' : form.errors.has('form.fullname')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Pinjam</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="borrow_date" v-model="form.borrow_date" :class="{'is-invalid' : form.errors.has('form.borrow_date')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Pengembalian</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <input name="return_date" v-model="form.return_date" :class="{'is-invalid' : form.errors.has('form.return_date')}" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Tanggal Pengembalian Aktual</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <date-picker name="actual_return_date" v-model="form.actual_return_date" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('actual_return_date') }" @change="getFine()"></date-picker>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-xs-12 control-label">Total Denda</label>
                                <div class="col-sm-9 col-md-9 col-xs-12">
                                    <money v-model="form.total_fine" class="form-control" readonly></money>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                        <a href="#" @click.prevent="returnBook()" v-show="editMode == false" class="btn btn-primary">Simpan</a>
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
import moment from 'moment'
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
                'aksi', 'title', 'author', 'isbn', 'published', 'fullname', 'borrow_date', 'return_date',
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
                    published: 'Tahun Terbit',
                    fullname: 'Nama Peminjam',
                    borrow_date: 'Tanggal Pinjam',
                    return_date: 'Tanggal Pengembalian',
                },
                sortable: ['title', 'author', 'isbn', 'published', 'fullname', 'borrow_date', 'return_date',],
                filterable: ['title', 'author', 'isbn', 'published', 'fullname', 'borrow_date', 'return_date',],
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
                fullname: '',
                borrow_date: '',
                return_date: '',
                actual_return_date: '',
                total_fine: '0',
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
        getFine(){
            var return_date = moment(this.form.return_date)
            var actual_return_date = moment(this.form.actual_return_date)
            var diff = moment.duration(actual_return_date.diff(return_date)).asDays();

            console.log(diff);
            var fine = 0;

            if (diff > 0) {
                fine = 2000 * diff
            }

            this.form.total_fine = fine;

        },

        editModal(book) {
            this.loadMember();
            $('#modal-default').modal('show');
            this.form.fill(book);
        },
        returnBook() {
            this.$Progress.start();
            swal({
                title: 'Mohon tunggu',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
                onOpen: () => {
                    swal.showLoading();
                    this.$Progress.start();
                    this.form.post('/api/transaksi/buku/kembalikan/'+this.form.id)
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
            axios.get('/api/transaksi/buku/pinjam/list').then(({
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
