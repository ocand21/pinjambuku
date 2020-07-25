<template>
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Home
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Data Users</span>
                        <span class="info-box-number">{{dataSummary.users}}</span>
                        <router-link to="/master-data/user">Lihat Selengkapnya...</router-link>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-user-tie"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Member</span>
                        <span class="info-box-number">{{dataSummary.members}}</span>
                        <router-link to="/master-data/member">Lihat Selengkapnya...</router-link>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Buku</span>
                        <span class="info-box-number">{{dataSummary.books}}</span>
                        <router-link to="/master-data/buku">Lihat Selengkapnya...</router-link>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-book"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Buku Dipinjam</span>
                        <span class="info-box-number">{{dataSummary.borrowed}}</span>
                        <router-link to="/transaksi/laporan-pinjam">Lihat Selengkapnya...</router-link>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Selamat Datang</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              Selamat beraktivitas
            </div>
            <!-- /.box-body -->
            
            <!-- /.box-footer-->
          </div>

    </section>
</div>
</template>

<script>
export default {

    data() {
        return {
            dataSummary: [],
        }
    },
    methods: {
        loadData() {
            axios.get('/api/dashboard').then(({
                data
            }) => (this.dataSummary = data));
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
